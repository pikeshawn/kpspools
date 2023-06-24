<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Customer;
use App\Models\GeneralNote;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $customers = '';

        if (User::isAdmin()) {
//            dd("ALL");

            $customers = Customer::allCustomers();

//            dd($customers);
        } else {
//            dd("Pool Guy");

            $customers = Customer::allCustomersTiedToUser();

//            dd($customers);
        }

        return Inertia::render('Customers/Index', [
            'customers' => $customers,
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
    public function create(): Response
    {
        //
        return Inertia::render('Customers/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $c = Customer::firstOrCreate([
            'phone_number' => $request->phoneNumber
        ],
            [
                'user_id' => 2,
                'last_name' => $request->lastName,
                'first_name' => $request->firstName,
                'type' => "Service",
                'service_day' => $request->serviceDay,
                'order' => 1000,
                'plan_duration' => "Monthly",
                'plan_price' => $request->planPrice,
                'chemicals_included' => $request->chemsIncluded,
                'assigned_serviceman' => $request->assignedServiceman,
                'phone_number' => $request->phoneNumber
            ]);

        Address::firstOrCreate([
            'customer_id' => $c->id
        ],
            [
                'customer_id' => $c->id,
                'address_line_1' => $request->address,
                'address_line_2' => null,
                'city' => $request->city,
                'state' => "AZ",
                'zip' => $request->zip,
                'community_gate_code' => $request->gateCode,
                'house_gate_has_lock' => 0,
            ]);

        // Redirect to a different page after the store operation
        return Redirect::route('customers')
            ->with('success', 'Data stored successfully');

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        $notes = DB::select('Select * from general_notes where customer_id = '
            . $customer->id . ' Order By updated_at DESC');

        $address = DB::select('Select * from addresses where customer_id = '
            . $customer->id);



        return Inertia::render('Customers/Show', [
            'customer' => $customer,
            'notes' => $notes,
            'address' => $address
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
