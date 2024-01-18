<?php

namespace App\Http\Controllers;

use App\Models\Address;
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

class CustomerController extends Controller
{

    use Passwordless;

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
//        dd('index');

        if (User::isAdmin()) {
            $customers = Customer::allCustomers();
        } else {
            $customers = Customer::allCustomersTiedToUser();
        }

        $servicemen = User::where('type', 'serviceman')->orderBy('name', 'asc')->where('active', 1)->get();

        return Inertia::render('Customers/Index', [
            'customers' => $customers,
            'servicemen' => $servicemen,
        ]);
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

    public function store_note(Request $request): RedirectResponse
    {
        $note = GeneralNote::firstOrCreate([
            'customer_id' => $request->customer_id,
            'note' => $request->note,
        ]);

        $customer = Customer::find($request->customer_id);

        return Redirect::route('general.notes', $customer->id);
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
        $url = "https://kpspools.com/login/onboard/". $token;

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

        $notes = DB::select('Select * from general_notes where customer_id = '
            . $customer->id . ' Order By updated_at DESC');

        $tasks = Task::allPickedUpTasksRelatedToSpecificCustomer($customer->id);
        $completedTasks = Task::allCompletedTasksRelatedToSpecificCustomer($customer->id);

        $serviceman = User::find($address->serviceman_id);

//        dd($tasks);


        return Inertia::render('Customers/Show', [
            'customer' => $customer,
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
    public function edit(Customer $customer): Response
    {
        return Inertia::render('Customers/Edit', [
            'customer' => [
                'id' => $customer->id,
                'first_name' => $customer->first_name,
                'middle_name' => $customer->middle_name,
                'last_name' => $customer->last_name,
                'type' => $customer->type,
                'plan' => $customer->plan,
                'service_day' => $customer->service_day,
            ],
        ]);
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
