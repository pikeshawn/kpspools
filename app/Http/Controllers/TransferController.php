<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
//        dd($request->address);

        $serviceManName = explode(" ", $request->address)[0];
        $serviceManId = explode(" ", $request->address)[1];
        $addressId = explode(" ", $request->address)[2];

        $address = Address::find($addressId);
        $customer = Customer::find($address->customer_id);


        $customer->assigned_serviceman = $serviceManName;
        $customer->serviceman_id = $serviceManId;;

        $address->assigned_serviceman = $serviceManName;
        $address->serviceman_id = $serviceManId;;

        $customer->save();
        $address->save();


    }

}
