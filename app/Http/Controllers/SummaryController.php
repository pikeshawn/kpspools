<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Customer;
use App\Models\ServiceStop;
use App\Models\User;
use App\Notifications\ServiceStopCompleted;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class SummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function customerSummary(Address $address): Response
    {
        //

        $customer = Customer::find($address->customer_id);

        return Inertia::render('Summary/customer', [
            'customer_name' => $customer->first_name.' '.$customer->last_name,
            'totalStopTime' => self::totalTime($customer->id),
            'totalServiceTime' => self::totalTimeByType($customer->id, 'Service Stop'),
            'totalRepairTime' => self::totalTimeByType($customer->id, 'Repairs'),
            'totalGreenPoolTime' => self::totalTimeByType($customer->id, 'Clear Green Pool'),

            'totalStops' => self::totalStops($customer->id),
            'totalServiceStops' => self::totalStopsByType($customer->id, 'Service Stop'),
            'totalRepairStops' => self::totalStopsByType($customer->id, 'Repair'),
            'totalGreenPoolStops' => self::totalStopsByType($customer->id, 'Clear Green Pool'),

            'totalAverageTime' => self::totalAverageTime($customer->id),
            'totalAverageClearGreenPoolTime' => self::totalAverageTimeByType($customer->id, 'Clear Green Pool'),
            'totalAverageServiceTime' => self::totalAverageTimeByType($customer->id, 'Service Stop'),
            'totalAverageRepairTime' => self::totalAverageTimeByType($customer->id, 'Repair'),

            'totalTabs' => self::totalTabs($customer->id),
            'totalLiquidChlorine' => self::totalLiquidChlorine($customer->id),
            'totalMuriaticAcid' => self::totalMuriaticAcid($customer->id),

            'totalTabPrice' => self::totalTabPrice($customer->id),
        ]);
    }

    private function totalTabPrice($customerId)
    {
        $c = Customer::find($customerId);

        return $c->getTabs()[0]->tabs * 2.25;
    }

    private function totalTabs($customerId)
    {
        $query = DB::select(
            'select sum(tabs_whole_mine) as tabs from service_stops where customer_id = '.$customerId

        );

        return $query[0]->tabs;
    }

    private function totalLiquidChlorine($customerId)
    {
        $query = DB::select(
            'select sum(liquid_chlorine) as lq from service_stops where customer_id = '.$customerId

        );

        return $query[0]->lq;
    }

    private function totalMuriaticAcid($customerId)
    {
        $query = DB::select(
            'select sum(liquid_acid) as la from service_stops where customer_id = '.$customerId

        );

        return $query[0]->la;
    }

    private function totalAverageTimeByType($customerId, $type)
    {
        $query = DB::select(
            'select ceiling(AVG(TIME_TO_SEC(TIMEDIFF(ss.time_out, ss.time_in))) / 60)
                            as averageTime
                    from service_stops ss
                            where customer_id = '.$customerId.'
                    and ss.service_type = "'.$type.'"'
        );

        return $query[0]->averageTime;
    }

    private function totalAverageTime($customerId)
    {
        $query = DB::select(
            'select ceiling(AVG(TIME_TO_SEC(TIMEDIFF(time_out,time_in))) / 60) as averageTime from service_stops where customer_id = '
            .$customerId
        );

        return $query[0]->averageTime;
    }

    private function totalStops($customerId)
    {
        $query = DB::select(
            'select count(*) as total from service_stops where customer_id = '
            .$customerId
        );

        return $query[0]->total;
    }

    private function totalStopsByType($customerId, $type)
    {
        $query = DB::select(
            'select count(*) as total from service_stops ss
                        where customer_id = '.$customerId.'
                        and ss.service_type = "'.$type.'"'
        );

        return $query[0]->total;
    }

    private function totalTime($customerId)
    {
        $query = DB::select(
            'select ceiling(SUM(TIME_TO_SEC(TIMEDIFF(time_out,time_in))) / 60) as total from service_stops ss where customer_id = '
            .$customerId
        );

        return $query[0]->total;
    }

    private function totalTimeByType($customerId, $type)
    {
        $query = DB::select(
            'select ceiling(SUM(TIME_TO_SEC(TIMEDIFF(time_out,time_in))) / 60) as total from service_stops ss
                        where customer_id = '.$customerId.'
                        and ss.service_type = "'.$type.'"'
        );

        return $query[0]->total;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Customer $customer): Response
    {
        return Inertia::render('ServiceStops/Create', [
            'customerId' => $customer->id,
            'customerName' => $customer->last_name,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
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
            'user_id' => Auth::user()->id,
        ]);

        $cust = Customer::find($request->id);

        $address = DB::select('select * from addresses a where a.customer_id ='.$cust->id);

        if ($request->service_type == 'Service Stop') {
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
            'customers' => $customers,
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
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceStop $serviceStop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceStop $serviceStop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceStop $serviceStop)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
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
