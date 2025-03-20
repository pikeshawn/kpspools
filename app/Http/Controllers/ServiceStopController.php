<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Customer;
use App\Models\Cya;
use App\Models\EmployeePayment;
use App\Models\Filter;
use App\Models\ServiceStop;
use App\Models\Task;
use App\Models\User;
use App\Notifications\GenericNotification;
use App\Notifications\OnMyWayNotification;
use App\Notifications\ServiceStopCompleted;
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
    public function index(Address $address): Response
    {
        //

        //        $serviceStops = ServiceStop::where('address_id', '=', $address->id)
        //            ->select([
        //                'id',
        //                'ph_level',
        //                'chlorine_level',
        //                'tabs_whole_mine',
        //                'tabs_crushed_mine',
        //                'tabs_whole_theirs',
        //                'tabs_crushed_theirs',
        //                'liquid_chlorine',
        //                'liquid_acid',
        //                'time_in',
        //                'time_out',
        //                'service_time',
        //                'service_type',
        //                'vacuum',
        //                'brush',
        //                'empty_baskets',
        //                'backwash',
        //                'powder_chlorine',
        //                'notes',
        //            ])->orderBy('time_in', 'desc')->get();

        $serviceStops = ServiceStop::where('address_id', '=', $address->id)->orderBy('created_at', 'desc')->get();

        $customer = Customer::find($address->customer_id);

        return Inertia::render('ServiceStops/Index', [
            'address' => $address,
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
    public function create(Address $address): Response
    {
        //        $address = DB::select('Select * from addresses where customer_id = '
        //            . $customer->id);

        //        dd($address);

        $customer = Customer::find($address->customer_id);

        //        $address = Address::where('customer_id', $customer->id)->get();

        $tasks = Task::where('customer_id', $customer->id)->where('status', 'pickedUp')->get();

        //        dd($customer->id);
        foreach ($tasks as $task) {
            $assigned = User::find($task->assigned);
            $task->assigned = $assigned->name;
        }

        $filter = Filter::getFilter($address->id);
        $cya = Cya::where('address_id', $address->id)->orderBy('tested_date', 'DESC')->first();

        $needsBackwash = Customer::needsBackwash($address->id, $filter);

        return Inertia::render('ServiceStops/Create', [
            'customerId' => $customer->id,
            'cya' => $cya,
            'lastBackwash' => $needsBackwash,
            'customer' => $customer,
            'equipment' => $filter,
            'address' => $address,
            'customerName' => $customer->last_name,
            'tasks' => $tasks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function notes(Address $address): Response
    {

        $customer = Customer::find($address->customer_id);

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
    public function store(Request $request): RedirectResponse
    {

        //        dd($request);

        $address = Address::find($request->address);
        $phLevel = $request->ph_level ?? 0;
        $chlorineLevel = $request->chlorine_level ?? 100;
        $start = new Carbon($request->timeIn);
        $end = new Carbon($request->timeOut);
        $cust = Customer::find($request->id);

        if ($request->service_type === 'Missed Service') {
            $serviceStop = self::missedService($request, $address, $phLevel, $chlorineLevel, $start, $end);
            self::sendMissedServiceNotification($serviceStop, Auth::user(), $cust);
        } else {
            $serviceStop = self::createServiceStop($request, $address, $phLevel, $chlorineLevel, $start, $end);
            $serviceStop->phosphateLevel = $request->phosphateLevel;
            //            dd($serviceStop);
            self::sendNotification($request, $cust, $serviceStop, $address);
        }

        if ($request->toCustomer) {
            return Redirect::route('customers.show', $address->id);
        } else {
            if (User::isAdmin()) {
                $customers = Customer::allCustomers();
            } else {
                $customers = Customer::allCustomersTiedToUser();
            }

            return Redirect::route('customers', [
                'customers' => $customers,
            ]);

        }

    }

    private function sendMissedServiceNotification($servicestop, $user, $customer)
    {
        Notification::route('vonage', $customer->phone_number)->notify(new GenericNotification(
            "Apologies, we couldn't service your pool today as we couldn't access your backyard at $servicestop->time_in. Please contact $user->name at $user->phone_number to make further arrangements, or you can reach out to Shawn at 480.703.4902 or 480.622.6441.\n\nKindly note, there may still be a charge for the service as access was beyond our control."
        ));

        Notification::route('vonage', '14806226441')->notify(new GenericNotification(
            "A Missed Service message was sent by $user->name to ".$customer->first_name.' '.$customer->last_name.' REASON:: '.$servicestop->notes
        ));
    }

    private function createServiceStop($request, $address, $phLevel, $chlorineLevel, $start, $end)
    {

        $f = Filter::where('customer_id', $request->id)->where('address_id', $address->id)->get();

        if ($f->isEmpty() && ! is_null($request->filter_type)) {
            Filter::firstOrCreate([
                'customer_id' => $request->id,
                'address_id' => $address->id,
                'type' => $request->filter_type,
            ]);
        } elseif ($f[0]->filter_type != $request->filter_type) {
            $filter = Filter::where('address_id', $address->id)->first();
            $filter->type = $request->filter_type;
            $filter->save();
        }

        if ($request->cya) {
            $cya = Cya::where('address_id', $address->id)->orderBy('tested_date', 'DESC')->first();
            $date = Carbon::now();
            $testedDate = $date->format('Y-m-d');
            if (is_null($cya)) {
                Cya::firstOrCreate([
                    'level' => $request->cya,
                    'address_id' => $address->id,
                    'tested_date' => $testedDate,
                ]);
            } else {
                if ($cya->level != $request->cya) {
                    $c = new Cya([
                        'level' => $request->cya,
                        'address_id' => $address->id,
                        'tested_date' => $testedDate,
                    ]);
                    $c->save();
                }
            }

        }

        //        dd($request);

        return ServiceStop::firstOrCreate([
            'customer_id' => $request->id,
            'address_id' => $address->id,
            'ph_level' => $phLevel,
            'chlorine_level' => $chlorineLevel,
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
            'super_black_algaecide' => $request->super_black_algaecide,
            'no_phos' => $request->no_phos,
            'user_id' => Auth::user()->id,
        ]);

    }

    private function missedService($request, $address, $phLevel, $chlorineLevel, $start, $end)
    {
        return ServiceStop::firstOrCreate([
            'user_id' => Auth::user()->id,
            'customer_id' => $request->id,
            'address_id' => $address->id,
            'ph_level' => $phLevel,
            'chlorine_level' => $chlorineLevel,
            'checked_chems' => $request->checkedChems,
            'time_in' => $request->timeIn,
            'time_out' => $request->timeOut,
            'service_time' => $start->diff($end)->format('%H:%I:%S'),
            'brush' => 0,
            'empty_baskets' => 0,
            'backwash' => 0,
            'powder_chlorine' => 0.0,
            'notes' => $request->notes,
            'salt_level' => $request->salt_level,
            'service_type' => $request->service_type,
        ]);
    }

    private function sendNotification($request, $cust, $serviceStop, $address)
    {

        //        dd($serviceStop);

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
            } elseif ($cust->phone_number == '14804448921') {
                Notification::route('vonage', '15103667985')
                    ->notify(new ServiceStopCompleted($serviceStop, $cust, $address, true));
            } elseif ($cust->phone_number == '16029894584') {
                Notification::route('vonage', '14806286607')
                    ->notify(new ServiceStopCompleted($serviceStop, $cust, $address, true));
                // Get the current system date
                $currentDate = Carbon::now();
                $futureDate = Carbon::parse('2025-06-01'); // You can set any future date
                if ($address->id === 34 && $currentDate->lessThan($futureDate)) {
                    Notification::route('vonage', '17202017174')
                        ->notify(new ServiceStopCompleted($serviceStop, $cust, $address, true));
                }
            }

            EmployeePayment::logServiceStopPayment($serviceStop);

            Notification::route('vonage', '14806226441')
                ->notify(new ServiceStopCompleted($serviceStop, $cust, $address, true));
        }
    }

    public function sendText(Request $request): RedirectResponse
    {
        Notification::route('vonage', $request->customerPhoneNumber)
            ->notify(new OnMyWayNotification($request->textMessage));

        Notification::route('vonage', '14806226441')
            ->notify(new OnMyWayNotification($request->textMessage, $request->customerName));

        //        dd($request->userPhoneNumber);

        Notification::route('vonage', $request->userPhoneNumber)
            ->notify(new OnMyWayNotification($request->textMessage, $request->customerName));

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
        $ss->powder_chlorine = $request->powder_chlorine ?? 0;
        $ss->backwash = $request->backwash;
        $ss->user_id = Auth::user()->getAuthIdentifier();
        $ss->vacuum = $request->vacuum;
        $ss->service_time = $request->serviceTime;
        $ss->time_out = $request->timeOut;
        $ss->liquid_acid = $request->acid;
        $ss->tabs_crushed_theirs = $request->tabsCrushedTheirs;
        $ss->tabs_whole_theirs = $request->tabsWholeTheirs;
        $ss->tabs_crushed_mine = $request->tabsCrushedMine;
        $ss->chlorine_level = $request->chlorine_level ?? 100;
        $ss->address_id = $request->addressId;
        $ss->id = $request->id;
        $ss->empty_baskets = $request->emptyBaskets;
        $ss->ph_level = $request->ph_level ?? 0;
        $ss->tabs_whole_mine = $request->tabsWholeMine;
        $ss->liquid_chlorine = $request->liquidChlorine;
        $ss->time_in = $request->timeIn;
        $ss->brush = $request->brush;
        $ss->notes = $request->notes;
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

        EmployeePayment::where('service_stop_id', $serviceStop->id)->delete();

        return \redirect()->route('customers');
    }
}
