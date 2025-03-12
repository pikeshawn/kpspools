<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ServiceStop;
use Carbon\Carbon;

class TransferController extends Controller
{
    //

    public function index()
    {
        $addresses = Address::where('active', 1)->get();
        $servicemen = User::where('type', 'serviceman')->orderBy('name', 'asc')->where('active', 1)->get();

        foreach ($addresses as $address){
            $address->transfer = false;
            $address->newServiceman = 'current';
            $customer = Customer::find($address->customer_id);
            $address->name = $customer->first_name . " " . $customer->last_name;
            $address->newServicemanId = null;
        }

        return Inertia::render('Transfer/Index', [
            'addresses' => $addresses,
            'servicemen' => $servicemen
        ]);
    }

    public function transfer(Request $request)
    {
//        dd($request->address);

        $customer = Customer::find($request->address['customer_id']);
        $address = Address::find($request->address['id']);

        $customer->assigned_serviceman = explode(" ", $request->address['newServicemanId'])[0];
        $customer->serviceman_id = explode(" ", $request->address['newServicemanId'])[1];;

        $address->assigned_serviceman = explode(" ", $request->address['newServicemanId'])[0];
        $address->serviceman_id = explode(" ", $request->address['newServicemanId'])[1];;

        $customer->save();
        $address->save();


    }

    public function storeFromCustomers(Request $request)
    {

        $serviceman = User::find($request->servicemanId);
        $address = Address::find($request->addressId);
        $customer = Customer::find($address->customer_id);

        $customer->assigned_serviceman = $serviceman->name;
        $customer->serviceman_id = $serviceman->id;;

        $address->assigned_serviceman = $serviceman->name;
        $address->serviceman_id = $serviceman->id;

        $customer->save();
        $address->save();



    }

    public function bulk()
    {
        $servicemen = User::where('type', 'serviceman')
            ->where('active', true)->get();

        return Inertia::render('Transfer/BulkTransfer', [
            'servicemen' => $servicemen
        ]);
    }

    public function getList(Request $request)
    {
        $dayOfRoute = $request->input('dayOfRoute');
        $servicemanId = $request->input('servicemanId');
        $option = $request->input('options');

        $pools = Address::join('customers', 'customers.id', '=', 'addresses.customer_id')
            ->where('addresses.serviceman_id', $servicemanId)
            ->where('addresses.service_day', $dayOfRoute)
            ->where('addresses.active', true)
            ->where('addresses.sold', '<>', true)
            ->select('addresses.service_day', 'customers.first_name', 'customers.last_name', 'addresses.id', 'addresses.assigned_serviceman', 'addresses.serviceman_id')
            ->get()
            ->map(function ($pool) {
                return [
                    'customerName' => $pool->first_name . ' ' . $pool->last_name,
                    'addressId' => $pool->id,
                    'servicemanName' => $pool->assigned_serviceman,
                    'service_day' => $pool->service_day
                ];
            });

        if ($option === 'completed') {
            $pools = $pools->filter(function ($pool) {
                return ServiceStop::where('address_id', $pool['addressId'])
                    ->where('created_at', '>=', Carbon::now()->startOfWeek())
                    ->exists();
            })->values();
        }

            if ($option === 'uncompleted') {
            $pools = $pools->reject(function ($pool) {
                return ServiceStop::where('address_id', $pool['addressId'])
                    ->where('created_at', '>=', Carbon::now()->startOfWeek())
                    ->exists();
            })->values();
        }

        return response()->json($pools);
    }

    public function doTransfer(Request $request)
    {
        $serviceman = User::find($request->servicemanId);
        $addressIds = $request->addressIds;

        foreach ($addressIds as $addressId) {
            $address = Address::find($addressId);
            if ($address) {
                $address->serviceman_id = $serviceman->id;
                $address->assigned_serviceman = $serviceman->name;
                $address->save();

                $customer = Customer::find($address->customer_id);
                if ($customer) {
                    $customer->serviceman_id = $serviceman->id;
                    $customer->assigned_serviceman = $serviceman->name;
                    $customer->save();
                }
            }
        }

        return response()->json(['message' => 'Transfer successful']);
    }

}
