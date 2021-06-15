<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ServiceStop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

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
                'vacuum',
                'brush',
                'empty_baskets',
                'backwash',
                'powder_chlorine',
                'notes'
            ])->get();

        $serviceStops = ServiceStop::where('customer_id', '=', $customer->id)->get();


        return Inertia::render('ServiceStops/Index', [
//            'filters' => \Illuminate\Support\Facades\Request::all('search', 'role', 'trashed'),
            'service_stops' => $serviceStops
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Customer $customer)
    {
        //
        return Inertia::render('ServiceStops/Create', [
            'customerId' => $customer->id
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
        //

//        dd($request);

        $serviceStop = ServiceStop::firstOrCreate([
                'customer_id' => $request->id,
                'address_id' => 2,
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
                'service_time' => "00:00:00",
                'vacuum' => true,
                'brush' => $request->brush,
                'empty_baskets' => $request->emptyBaskets,
                'backwash' => $request->backwash,
                'powder_chlorine' => $request->powder_chlorine,
                'notes' => $request->notes
            ]);

//        Auth::user()->service_stops()->create(
//            \Illuminate\Support\Facades\Request::validate([
//                'ph_level' => $request->php_level,
//                'chlorine_level' => $request->php_level,
//                'organization_id' => ['nullable', Rule::exists('organizations', 'id')->where(function ($query) {
//                    $query->where('account_id', Auth::user()->account_id);
//                })],
//                'email' => ['nullable', 'max:50', 'email'],
//                'phone' => ['nullable', 'max:50'],
//                'address' => ['nullable', 'max:150'],
//                'city' => ['nullable', 'max:50'],
//                'region' => ['nullable', 'max:50'],
//                'country' => ['nullable', 'max:2'],
//                'postal_code' => ['nullable', 'max:25'],
//            ])
//        );
//

        $serviceStops = ServiceStop::where('customer_id', '=', $request->id)->get();


        return Inertia::render('ServiceStops/Index', [
//            'filters' => \Illuminate\Support\Facades\Request::all('search', 'role', 'trashed'),
            'service_stops' => $serviceStops
        ]);


//        return Redirect::route('customers.index', $serviceStop)->with('success', 'Service Stop Created.');

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
    }
}
