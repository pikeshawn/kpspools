<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    //
    /**
     * Show the form for creating a new resource.
     */
    public function create(Customer $customer): Response
    {
        $address = DB::select('Select * from addresses where customer_id = '
            . $customer->id);

//        dd($customer->id);

        return Inertia::render('Task/Create', [
            'customerId' => $customer->id,
            'customer' => $customer,
            'customerName' => $customer->last_name,
        ]);
    }
}
