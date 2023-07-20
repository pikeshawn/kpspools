<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Customer;
use App\Models\ServiceStop;
use App\Models\User;
use App\Notifications\ServiceStopCompleted;
use App\Notifications\OnMyWayNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ServiceStopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Customer $customer): Response
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
                'notes',
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
        $address = DB::select('Select * from addresses where customer_id = '
            . $customer->id);

//        dd($customer->id);

        return Inertia::render('ServiceStops/Create', [
            'customerId' => $customer->id,
            'customer' => $customer,
            'address' => $address,
            'customerName' => $customer->last_name,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function notes(Customer $customer): Response
    {
        $notes = DB::select('select id, updated_at,
            notes, service_type from service_stops where customer_id = '.$customer->id.' order by updated_at DESC');

        return Inertia::render('ServiceStops/Notes', [
            'customer_name' => $customer->first_name.' '.$customer->last_name,
            'notes' => $notes,
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
            'checked_chems' => $request->checkedChems,
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
            'salt_level' => $request->salt_level,
            'service_type' => $request->service_type,
            'serviceman_id' => Auth::user()->id,
        ]);

        $cust = Customer::find($request->id);

        $address = DB::select('select * from addresses a where a.customer_id ='.$cust->id);

        if ($request->service_type == 'Service Stop') {
            if ($cust->phone_number) {
//                $cust->notify(new ServiceStopCompleted($serviceStop, $cust, $address));
                Notification::route('vonage', $cust->phone_number)
                    ->notify(new ServiceStopCompleted($serviceStop, $cust, $address));
            }

            if ($cust->phone_number == '14802966330') {
                Notification::route('vonage', '14802966333')
                    ->notify(new ServiceStopCompleted($serviceStop, $cust, $address, true));
            } elseif ($cust->phone_number == '14807038320') {
                Notification::route('vonage', '14807038340')
                    ->notify(new ServiceStopCompleted($serviceStop, $cust, $address, true));
            } elseif ($cust->phone_number == '17607748119') {
                Notification::route('vonage', '17608318534')
                    ->notify(new ServiceStopCompleted($serviceStop, $cust, $address, true));
            } elseif ($cust->phone_number == '14802136170') {
                Notification::route('vonage', '16196513385')
                    ->notify(new ServiceStopCompleted($serviceStop, $cust, $address, true));
            } elseif ($cust->phone_number == '14808265558') {
                Notification::route('vonage', '14805500232')
                    ->notify(new ServiceStopCompleted($serviceStop, $cust, $address, true));
            }

            Notification::route('vonage', '14806226441')
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
    }

    function sendText(Request $request): RedirectResponse
    {
        Notification::route('vonage', $request->customerPhoneNumber)
            ->notify(new OnMyWayNotification($request->textMessage));

        return Redirect::back()->with('success', 'Text Sent');
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
     */
    public function edit(ServiceStop $serviceStop): Response
    {
        //

        $customer = Customer::find($serviceStop->customer_id);

        return Inertia::render('ServiceStops/Edit', [
            'serviceStop' => $serviceStop,
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\ServiceStop  $serviceStop
     */
    public function update(Request $request): RedirectResponse
    {
        //

        $ss = ServiceStop::find($request->id);
        $ss->powder_chlorine = $request->powder_chlorine;
        $ss->backwash = $request->backwash;
        $ss->vacuum = $request->vacuum;
        $ss->service_time = $request->serviceTime;
        $ss->time_out = $request->timeOut;
        $ss->liquid_acid = $request->acid;
        $ss->tabs_crushed_theirs = $request->tabsCrushedTheirs;
        $ss->tabs_whole_theirs = $request->tabsWholeTheirs;
        $ss->tabs_crushed_mine = $request->tabsCrushedMine;
        $ss->chlorine_level = $request->chlorine_level;
        $ss->address_id = $request->addressId;
        $ss->id = $request->id;
        $ss->empty_baskets = $request->emptyBaskets;
        $ss->ph_level = $request->ph_level;
        $ss->tabs_whole_mine = $request->tabsWholeMine;
        $ss->liquid_chlorine = $request->liquidChlorine;
        $ss->time_in = $request->timeIn;
        $ss->brush = $request->brush;
        $ss->notes = $request->notes;
        $ss->serviceman_id = $request->servicemanId;
        $ss->service_type = $request->service_type;
        $ss->save();

        $customer = Customer::find($request->customer_id);

        return Redirect::route('service_stops', $customer->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceStop $serviceStop)
    {
        $serviceStop->delete();

        return \redirect()->route('customers');
    }
}
