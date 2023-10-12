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

    public function store(Request $request): Response
    {
        dd($request);

//      get all data

//      add to db with first or create
//        - add task to db
//        - add latest status to task table
//        - add status to status table

//      process after data has been add to db
//        - send notification for approval - if a part, repair, or above preapproval amount
//          - can start as notification to contact shawn
//          - future will have the customer go on the app
//          - auto approval for items below a certain amount

//      redirect back or to another page
//        - redirect to customer page
    }
}
