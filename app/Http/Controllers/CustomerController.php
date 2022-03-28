<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $customers = DB::select('select c.first_name, c.last_name, c.id, c.service_day, c.assigned_serviceman,
       a.community_gate_code, a.address_line_1, a.city, a.zip
from customers c
         join addresses a on c.id = a.customer_id
where c.order is not NULL order By c.order DESC');

        $now = Carbon::now();
        $startOfWeek = $now->startOfWeek(CarbonInterface::MONDAY)->format('Y-m-d H:i');
        $endOfWeek = $now->endOfWeek(CarbonInterface::SUNDAY)->format('Y-m-d H:i');

        $startOfWeek = new Carbon($startOfWeek);
        $endOfWeek = new Carbon($endOfWeek);

        foreach ($customers as $customer) {

            $stops = DB::select('select count(ss.time_in) as stops
from service_stops ss
where ss.customer_id = ' . $customer->id . ' order by ss.time_in DESC Limit 1');

            if ($stops[0]->stops > 0) {

                $lastServiceStop = DB::select('select ss.time_in
from service_stops ss
where ss.customer_id = ' . $customer->id . ' order by ss.time_in DESC Limit 1')[0]->time_in;


                $lastServiceStop = new Carbon($lastServiceStop);

                if ($lastServiceStop > $startOfWeek && $lastServiceStop < $endOfWeek) {
                    $customer->completed = true;
                } else {
                    $customer->completed = false;
                }
            }

        }

        return Inertia::render('Customers/Index', [
            'customers' => $customers
        ]);


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
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
