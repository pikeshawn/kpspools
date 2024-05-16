<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RouteController extends Controller
{
    //

    public function index()
    {

        $customers = Customer::select([
            'addresses.id',
            'customers.first_name',
            'customers.last_name',
            'addresses.order',
            'addresses.service_day'
        ])
            ->join('addresses', 'customers.id', '=', 'addresses.customer_id')
            ->where(
                'addresses.serviceman_id',
                Auth::user()->getAuthIdentifier())
            ->orderBy('addresses.service_day')
            ->orderBy('addresses.order')
            ->get();

//        dd($customers[0]);

        return Inertia::render('Route/Index', [
            'customers' => $customers,
        ]);
    }

    public function store(Request $request)
    {
//        dd();
        foreach(json_decode($request->getContent()) as $route){
            $address = Address::find($route->id);
            $address->order = $route->order;
            $address->save();
        }

        if (User::isAdmin()) {
            $customers = Customer::allCustomers($request->service_day);
        } else {
            $customers = Customer::allCustomersTiedToUser($request->service_day);
        }
        return Inertia::render('Customers/Index', [
            'customers' => $customers,
        ]);
    }
}
