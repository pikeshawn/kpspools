<?php

namespace App\Http\Controllers;

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

        $customers = Customer::select(['id', 'first_name', 'last_name', 'order', 'service_day'])
            ->where('user_id', Auth::user()
                ->getAuthIdentifier())->orderBy('service_day')->orderBy('order')->get();

        return Inertia::render('Route/Index', [
            'customers' => $customers,
        ]);
    }

    public function store(Request $request)
    {
//        dd();
        foreach(json_decode($request->getContent()) as $route){
            $customer = Customer::find($route->id);
            $customer->order = $route->order;
            $customer->save();
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
}
