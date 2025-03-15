<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Owner;
use App\Models\Customer;
use App\Models\PasswordlessToken;
use App\Models\Task;
use App\Models\GeneralNote;
use App\Models\User;
use App\Notifications\GenericNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Notification;
use App\Traits\Passwordless;
use Carbon\Carbon;

class CustomerController extends Controller
{

    use Passwordless;

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
//        dd('index');

        // Get the current date and time
        $currentDateTime = Carbon::now();

// Get the day of the week (0 for Sunday, 1 for Monday, ..., 6 for Saturday)
        $dayOfWeek = $currentDateTime->dayOfWeek;

        $dayOfWeekText = $currentDateTime->format('l');


        if (User::isAdmin()) {
            $customers = Customer::allCustomers($dayOfWeekText);
        } else {
            $customers = Customer::allCustomersTiedToUser($dayOfWeekText);
        }

        $servicemen = User::where('type', 'serviceman')->orderBy('name', 'asc')->where('active', 1)->get();

        return Inertia::render('Customers/Index', [
            'customers' => $customers,
            'servicemen' => $servicemen,
            'currentDay' => strtolower(Carbon::now()->timezone(config('app.timezone'))->format('l'))
        ]);
    }

    public function bulkNotify()
    {
        $servicemen = User::where('type', 'serviceman')
            ->where('active', true)->get();

        return Inertia::render('Notification/BulkNotification', [
            'servicemen' => $servicemen
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $results = Owner::where('ownership', 'LIKE', "%{$query}%")
            ->orWhere('mailing_address_1', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['ownership', 'mailing_address_1', 'mailing_city', 'mailing_zip', 'sale_date', 'sale_price']);

        return response()->json($results);
    }

    public function getCustomer(Request $request)
    {

        return Address::join('customers', 'customers.id', '=', 'addresses.customer_id')
            ->where('customers.last_name', 'like', "%{$request->customer}%")
            ->selectRaw("CONCAT(addresses.id) as addressId,
                 CONCAT(customers.id) as customerId,
                 CONCAT(customers.first_name, ' ', customers.last_name) as name,
                 CONCAT(addresses.address_line_1, ', ', addresses.city, ' ', addresses.zip) as `address`")
            ->get();


//        return Customer::where('last_name', 'like', "%{$request->customer}%")->get();
    }

    public function notify(Request $request)
    {
        // Validate the input
        $request->validate([
            'servicemanId' => 'required|exists:users,id',
            'sick' => 'required|boolean',
            'message' => 'nullable|string',
            'addressIds' => 'required|array'
        ]);

        // Retrieve the serviceman
        $serviceman = User::find($request->servicemanId);

        // Determine the message
        foreach ($request->addressIds as $addressId) {
            $address = Address::find($addressId);
            $customer = Customer::find($address->customer_id);

            if (!$customer) {
                continue; // Skip if no customer is found
            }

            $message = $request->sick
                ? "{$serviceman->name} was feeling a bit under the weather, so he was unable to service your pool today at {$address->address_line_1}. He will be servicing it tomorrow. Sorry for the delay."
                : $request->message;

            // Send notification to customer via Vonage (SMS)
            Notification::route('vonage', $customer->phone_number)
                ->notify(new GenericNotification($message));
        }

        return response()->json(['message' => 'Notifications sent successfully.'], 200);
    }

    public function getCustomersForDay($day)
    {
//        dd($day);

        if (User::isAdmin()) {
            $customers = Customer::allCustomers(ucfirst($day));
        } else {
            $customers = Customer::allCustomersTiedToUser(ucfirst($day));
        }

//        $servicemen = User::where('type', 'serviceman')->orderBy('name', 'asc')->where('active', 1)->get();

//        return $customers;

        return response()->json([
            'success' => true,
            'customers' => $customers,
        ]);

//        return Inertia::render('Customers/Index', [
//            'customers' => $customers,
//            'servicemen' => $servicemen,
//        ]);
    }

    public function getNames(Request $request)
    {
        $query = Customer::select(
            'customers.first_name',
            'customers.last_name',
            'customers.phone_number', // Added phone number
            'addresses.address_line_1',
            'addresses.id as addressId'
        )
            ->join('addresses', 'customers.id', '=', 'addresses.customer_id');

        // Apply filters based on the search input
        if ($request->has('name') && !empty($request->name)) {
            $query->where(function ($q) use ($request) {
                $q->where('customers.first_name', 'like', "{$request->name}%")
                    ->orWhere('customers.last_name', 'like', "{$request->name}%")
                    ->orWhere('customers.phone_number', 'like', "{$request->name}%");
            });
        }

//        if ($request->has('phone') && !empty($request->phone)) {
//            $query->orWhere('customers.phone_number', 'like', "%{$request->phone}%");
//        }

        // Admin-specific condition
        if (!User::isAdmin()) {
            $query->where('addresses.active', 1)
                ->where('addresses.sold', 0);
        }

        $customers = $query->orderByDesc('addresses.order')->get();

        return response()->json($customers);
    }

    public function notes(Customer $customer): Response
    {
        $notes = DB::select('Select * from general_notes where customer_id = '
            . $customer->id . ' Order By updated_at DESC');

        return Inertia::render('Customers/Notes', [
            'customer_name' => $customer->first_name . ' ' . $customer->last_name,
            'customer_id' => $customer->id,
            'notes' => $notes,
        ]);
    }

    public function new_note(Customer $customer): Response
    {
        return Inertia::render('Customers/NewNote', [
            'customer_id' => $customer->id,
            'customer_name' => $customer->first_name . ' ' . $customer->last_name,
        ]);
    }

    public function store_note(Request $request)
    {
        GeneralNote::firstOrCreate([
            'customer_id' => $request->customerId,
            'address_id' => $request->addressId,
            'note' => $request->note
        ]);

        $notes = GeneralNote::where('address_id', $request->addressId)->get();

        return \response()->json($notes);

//        $customer = Customer::find($request->customer_id);

//        return Redirect::route('general.notes', $customer->id);



    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($userId): Response
    {
        //

        $customer = Customer::where('user_id', $userId)->get();
        $address = Address::where('customer_id', $customer[0]->id)->get();

        return Inertia::render('Customers/Create', [
            'prospective' => $customer[0],
            'address' => $address[0]
        ]);
    }


    /**
     * Show the form for adding a new customer.
     */
    public function add(): Response
    {
        //
        return Inertia::render('Customers/Add');
    }

    public function addStore(Request $request)
    {

//        dd($request);

        $user = User::firstOrCreate([
            'phone_number' => $request->phoneNumber
        ], [
            'name' => $request->firstName . ' ' . $request->lastName,
            'email' => $request->firstName . rand(0, 1000000) . '@example.com',
            'password' => bcrypt('Welcome1234'),
            'phone_number' => $request->phoneNumber,
            'is_admin' => false,
            'type' => 'customer',
            'active' => true,
            'terms_and_conditions' => false,
            'privacy_policy' => false
        ]);

        if ($user) {
            $customer = Customer::firstOrCreate([
                'phone_number' => $request->phoneNumber
            ], [
                'user_id' => $user->id,
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'active' => true,
                'type' => 'Service',
                'service_day' => 'Saturday',
                'order' => 1000,
                'plan_duration' => 'monthly',
                'plan_price' => $request->planPrice,
                'chemicals_included' => $request->chemsIncluded,
                'assigned_serviceman' => 'Shawn',
                'phone_number' => $request->phoneNumber,
                'terms' => 'begin',
                'autopay' => false,
                'serviceman_id' => 2
            ]);

            Address::firstOrCreate([
                'address_line_1' => $request->address
            ], [
                'customer_id' => $customer->id,
                'address_line_1' => $request->address,
                'city' => $request->city,
                'state' => 'AZ',
                'zip' => $request->zip,
                'community_gate_code' => $request->gateCode,
                'house_gate_has_lock' => $request->gateCode,
                'serviceman_id' => 2,
                'service_day' => 'Saturday',
                'order' => 1000,
                'active' => true,
                'type' => 'Service',
                'plan_duration' => 'monthly',
                'plan_price' => $request->planPrice,
                'chemicals_included' => $request->chemsIncluded,
                'assigned_serviceman' => 'Shawn',
                'terms' => 'begin',
                'sold' => false
            ]);

            if ($request->initiateBid) {
                $initiatedBid = app('App\Http\Controllers\InitiateBidController')->initiateBid($request);
                $initiatedBidJSON = json_decode($initiatedBid);
                // Get the current date and time
                $now = Carbon::now();
                // Format the date as YYYY-MM-DD
                $startDate = $now->format('Y-m-d');
                app('App\Http\Controllers\InitiateBidController')->addTasks($request->tasks, $initiatedBidJSON->job->id, $initiatedBidJSON->job->customer_id, $startDate);
                app('App\Http\Controllers\InitiateBidController')->submitBid($initiatedBidJSON->job->id, $initiatedBidJSON->job->customer_id);
                app('App\Http\Controllers\InitiateBidController')->updateCustomerTable($initiatedBidJSON->job->customer_id, $customer->id);
            }

            $user->assignRole('customer');

            return Redirect::route('customers')->with('success', 'Customer and address stored successfully');
        }

        return Redirect::route('customers')->with('error', 'Failed to store customer and address');
    }

    public function updateCustomer(Request $request)
    {

        $customerUser = $request->customerUser['_value'];
        $customer = $request->customer['_value'];
//        $addresses = $request->addresses['_value'];



        $cust = Customer::findOrFail($customer['id']);
        $cust->first_name = $customer['first_name'];
        $cust->middle_name = $customer['middle_name'];
        $cust->last_name = $customer['last_name'];
        $cust->phone_number = $customer['phone_number'];
        $cust->terms = $customer['terms'];
        $cust->jemmson_id = $customer['jemmson_id'];
        $cust->active = $customer['active'];
        $cust->autopay = $customer['autopay'];
        $cust->date_to_run_card = $customer['date_to_run_card'];
        $cust->payment_type = $customer['payment_type'];
        $cust->save();

        $custUser = User::findOrFail($customerUser['id']);
        $custUser->email = $customerUser['email'];
        $custUser->name = $customerUser['name'];
        $custUser->phone_number = $customerUser['phone_number'];
        $custUser->save();



        foreach($request->addresses as $address){

            if ($customer['active']) {
                $active = $address['active'];
            } else {
                $active = false;
            }

            $add = Address::findOrFail($address['id']);
            $add->service_day = $address['service_day'];
            $add->address_line_1 = $address['address_line_1'];
            $add->city = $address['city'];
            $add->state = $address['state'];
            $add->zip = $address['zip'];
            $add->community_gate_code = $address['community_gate_code'];
            $add->plan_duration = $address['plan_duration'];
            $add->plan_price = $address['plan_price'];
            $add->chemicals_included = $address['chemicals_included'];
            $add->active = $active;
            $add->sold = $address['sold'];
            $add->serviceman_id = $address['serviceman_id'];

            $add->save();
        }





//        // Update customer details
//        $customer = Customer::findOrFail($validatedData['id']);
//        $customer->update($validatedData);
//
//        // 🔥 Update or Create Addresses
//        foreach ($validatedData['addresses'] as $addressData) {
//            Address::updateOrCreate(
//                ['id' => $addressData['id'] ?? null], // Find by ID if exists
//                array_merge($addressData, ['customer_id' => $customer->id])
//            );
//        }

        return response()->json(['message' => 'Customer updated successfully'], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {

//        dd($request->notes);


        $u = User::find($request->userId);
        $u->name = $request->firstName . " " . $request->lastName;
        $u->phone_number = $request->phoneNumber;
        $u->active = 1;
        $u->type = 'customer';

        $u->save();

        $c = Customer::find($request->customerId);
        $c->first_name = $request->firstName;
        $c->last_name = $request->lastName;
        $c->service_day = $request->serviceDay;
        $c->chemicals_included = $request->chemsIncluded;
        $c->phone_number = $request->phoneNumber;
        $c->plan_price = $request->planPrice;

        $c->save();

        $a = Address::find($request->addressId);
        $a->address_line_1 = $request->address;
        $a->city = $request->city;
        $a->state = "AZ";
        $a->zip = $request->zip;
        $a->service_day = $request->serviceDay;
        $a->community_gate_code = $request->gateCode;

        $a->save();


        GeneralNote::firstOrCreate([
            'customer_id' => $c->id
        ],
            [
                'customer_id' => $c->id,
                'note' => $request->notes
            ]);

        // create a one time passcode
        $token = self::generateToken($u->id);

        // create a url based on that token
        $url = "https://kpspools.com/login/onboard/" . $token;

        $message = "Thank you for becoming a new KPS Pools customer. Please use the link here to finish the onboarding process. $url";
        Notification::route('vonage', $c->phone_number)->notify(
            new GenericNotification($message));

        // Redirect to a different page after the store operation
        return Redirect::route('customers')
            ->with('success', 'Data stored successfully');

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address): Response
    {
//        dd($address);

        $customer = Customer::find($address->customer_id);

//        dd($customer);


//        if (User::isAdmin()) {
//            $customers = Customer::allCustomers();
//        } else {
//            $customers = Customer::allCustomersTiedToUser();
//        }


        $notes = DB::select('Select * from general_notes where address_id = '
            . $address->id . ' Order By updated_at DESC');

        $tasks = Task::allPickedUpTasksRelatedToSpecificCustomer($address->id);
        $completedTasks = Task::allCompletedTasksRelatedToSpecificCustomer($address->id);

        $serviceman = User::find($address->serviceman_id);

//        dd($tasks);


        return Inertia::render('Customers/Show', [
            'customer' => $customer,
            'customers' => null,
            'notes' => $notes,
            'address' => $address,
            'tasks' => $tasks,
            'completedTasks' => $completedTasks,
            'serviceman' => $serviceman
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($addressId)
    {
        // Find the address by ID
        $address = Address::findOrFail($addressId);

        // Find the associated customer and user
        $customer = Customer::findOrFail($address->customer_id);
        $customerUser = User::findOrFail($customer->user_id);

        // Fetch active servicemen for dropdown
        $servicemen = User::where('type', 'serviceman')->where('active', true)->get(['id', 'name']);

        return Inertia::render('Customers/Edit', [
            'customerUser' => $customerUser,
            'customer' => $customer,
            'addressId' => $addressId,
            'addresses' => Address::where('customer_id', $customer->id)->get(),
            'servicemen' => $servicemen,
        ]);
    }

    public function changeActiveStatus(Request $request)
    {
        $address = Address::find($request->addressId);
        $customer = Customer::find($address->customer_id);

        if ($request->active){
            $address->serviceman_id = '2';
            $address->service_day = 'Saturday';
            $address->assigned_serviceman = 'Shawn';
            $customer->serviceman_id = '2';
            $customer->service_day = 'Saturday';
            $customer->assigned_serviceman = 'Shawn';
            $address->active = true;
            $customer->active = true;
        } else {
            $address->active = false;
            $customer->active = false;
        }

        $address->save();
        $customer->save();





    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Models\Customer $customer
     */
    public function update(Request $request): RedirectResponse
    {
        //

        $note = GeneralNote::find($request->note['id']);
        $note->note = $request->note['note'];
        $note->save();

        $customer = Customer::find($request->note['customer_id']);

//        dd($customer->id);

        return Redirect::route('general.notes', $customer->id);
    }

    public function showNote(GeneralNote $generalNote): Response
    {
        //

        return Inertia::render('Customers/EditNote', [
            'note' => $generalNote,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Customer $customer
     */
    public function destroy(GeneralNote $generalNote): RedirectResponse
    {
        $customer = Customer::find($generalNote->customer_id);

        $generalNote->delete();

        return Redirect::route('general.notes', $customer->id);
    }
}
