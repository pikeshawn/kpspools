<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Address;
use App\Models\ServiceStop;
use App\Models\User;
use App\Notifications\ServiceStopCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;
use Carbon\Carbon;

class ServiceStopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Customer $customer)
    {
        //

        $serviceStops = ServiceStop::where('customer_id', '=', $customer->id)
            ->select([
                'id',
                'ph_level',
                'chlorine_level',
                'tabs_whole_mine',
                'tabs_crushed_mine',
                'tabs_whole_theirs',
                'tabs_crushed_theirs',
                'liquid_chlorine',
                'liquid_acid',
                'time_in',
                'time_out',
                'service_time',
                'service_type',
                'vacuum',
                'brush',
                'empty_baskets',
                'backwash',
                'powder_chlorine',
                'notes'
            ])->orderBy('time_in', 'desc')->get();

        $serviceStops = ServiceStop::where('customer_id', '=', $customer->id)->orderBy('created_at', 'desc')->get();

        return Inertia::render('ServiceStops/Index', [
            'service_stops' => $serviceStops,
            'customer_name' => $customer->last_name,
            'customer_id' => $customer->id,
        ]);
    }

    private function totalAverageTimeByType($customerId, $type)
    {
        $query = DB::select(
            'select ceiling(AVG(TIME_TO_SEC(TIMEDIFF(ss.time_out, ss.time_in))) / 60)
                            as averageTime
                    from service_stops ss
                            where customer_id = ' . $customerId . '
                    and ss.service_type = "' . $type . '"'
        );

        return $query[0]->averageTime;
    }

    private function totalAverageTime($customerId)
    {
        $query = DB::select(
            'select ceiling(AVG(TIME_TO_SEC(TIMEDIFF(time_out,time_in))) / 60) as averageTime from service_stops where customer_id = '
            . $customerId
        );

        return $query[0]->averageTime;
    }

    private function totalStops($customerId)
    {
        $query = DB::select(
            'select count(*) as total from service_stops where customer_id = '
            . $customerId
        );

        return $query[0]->total;
    }

    private function totalStopsByType($customerId, $type)
    {
        $query = DB::select(
            'select count(*) as total from service_stops ss
                        where customer_id = ' . $customerId . '
                        and ss.service_type = "' . $type . '"'
        );

        return $query[0]->total;
    }

    private function totalTime($customerId)
    {
        $query = DB::select(
            'select ceiling(SUM(TIME_TO_SEC(TIMEDIFF(time_out,time_in))) / 60) as total from service_stops ss where customer_id = '
            . $customerId
        );

        return $query[0]->total;
    }

    private function totalTimeByType($customerId, $type)
    {
        $query = DB::select(
            'select ceiling(SUM(TIME_TO_SEC(TIMEDIFF(time_out,time_in))) / 60) as total from service_stops ss
                        where customer_id = ' . $customerId . '
                        and ss.service_type = "' . $type . '"'
        );

        return $query[0]->total;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Customer $customer)
    {
        return Inertia::render('ServiceStops/Create', [
            'customerId' => $customer->id,
            'customerName' => $customer->last_name
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notes(Customer $customer)
    {

        $notes = DB::select("select id, updated_at,
            notes, service_type from service_stops where customer_id = " . $customer->id . " order by updated_at DESC");

        return Inertia::render('ServiceStops/Notes', [
            'customer_name' => $customer->first_name . " " . $customer->last_name,
            'notes' => $notes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $address = Address::select(['id'])->where('customer_id', '=', $request->id)->get()->first();

        $start = new Carbon($request->timeIn);
        $end = new Carbon($request->timeOut);

        $serviceStop = ServiceStop::firstOrCreate([
            'customer_id' => $request->id,
            'address_id' => $address['id'],
            'ph_level' => $request->ph_level,
            'chlorine_level' => $request->chlorine_level,
            'tabs_whole_mine' => $request->tabsWholeMine,
            'tabs_crushed_mine' => $request->tabsCrushedMine,
            'tabs_whole_theirs' => $request->tabsWholeTheirs,
            'tabs_crushed_theirs' => $request->tabsCrushedTheirs,
            'liquid_chlorine' => $request->liquidChlorine,
            'liquid_acid' => $request->acid,
            'time_in' => $request->timeIn,
            'time_out' => $request->timeOut,
            'service_time' => $start->diff($end)->format('%H:%I:%S'),
            'vacuum' => $request->vacuum,
            'brush' => $request->brush,
            'empty_baskets' => $request->emptyBaskets,
            'backwash' => $request->backwash,
            'powder_chlorine' => $request->powder_chlorine,
            'notes' => $request->notes,
            'service_type' => $request->service_type,
            'serviceman_id' => Auth::user()->id
        ]);

        $cust = Customer::find($request->id);

        $address = DB::select('select * from addresses a where a.customer_id =' . $cust->id);

        if ($request->service_type == "Service Stop") {
            if ($cust->phone_number) {
                $cust->notify(new ServiceStopCompleted($serviceStop, $cust, $address));
            }

            Notification::route('nexmo', '14806226441')
                ->notify(new ServiceStopCompleted($serviceStop, $cust, $address, true));
        }

        if (User::isAdmin()) {

            $customers = Customer::allCustomers();

        } else {

            $customers = Customer::allCustomersTiedToUser();

        }

        return Inertia::render('Customers/Index', [
            'customers' => $customers
        ]);

//        return Inertia::render('Customers/Index', [
//            // 'filters' => \Illuminate\Support\Facades\Request::all('search', 'role', 'trashed'),
//            'service_stops' => $serviceStops,
//            'customer_name' => $customer->last_name,
//            'customer_id' => $customer->id
//        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ServiceStop $serviceStop
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceStop $serviceStop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ServiceStop $serviceStop
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceStop $serviceStop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ServiceStop $serviceStop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceStop $serviceStop)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ServiceStop $serviceStop
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceStop $serviceStop)
    {
        //

//        dd($serviceStop->customer_id);

//        $customer = Customer::find($serviceStop->customer_id);

        $serviceStop->delete();

//        return Redirect::route('service_stops', [$serviceStop->customer_id], 303);

//        return \redirect()->route('service_stops', [$serviceStop->customer_id], );
        return \redirect()->route('customers');

    }
}
