<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Cassandra\Keyspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\GeneralNote;
use Illuminate\Support\Facades\Redirect;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
            'customers' => $customers
        ]);

    }

    public function notes(Customer $customer)
    {
        $notes = DB::select('Select * from general_notes where customer_id = '
            . $customer->id . ' Order By updated_at DESC');

        return Inertia::render('Customers/Notes', [
            'customer_name' => $customer->first_name . " " . $customer->last_name,
            'customer_id' => $customer->id,
            'notes' => $notes
        ]);

    }

    public function new_note(Customer $customer)
    {

        return Inertia::render('Customers/NewNote', [
            'customer_id' => $customer->id,
            'customer_name' => $customer->first_name . " " . $customer->last_name
        ]);
    }

    public function store_note(Request $request)
    {
        $note = GeneralNote::firstOrCreate([
            'customer_id' => $request->customer_id,
            'note' => $request->note
        ]);

        $customer = Customer::find($request->customer_id);

        return Redirect::route('general.notes', $customer->id);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return Inertia::render('Customers/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
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
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //

        $note = GeneralNote::find($request->note['id']);
        $note->note = $request->note['note'];
        $note->save();

        $customer = Customer::find($request->note['customer_id']);

//        dd($customer->id);

        return Redirect::route('general.notes', $customer->id);

    }


    public function showNote(GeneralNote $generalNote)
    {
        //

        return Inertia::render('Customers/EditNote', [
            'note' => $generalNote
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(GeneralNote $generalNote)
    {

        $customer = Customer::find($generalNote->customer_id);

        $generalNote->delete();

        return Redirect::route('general.notes', $customer->id);

    }
}
