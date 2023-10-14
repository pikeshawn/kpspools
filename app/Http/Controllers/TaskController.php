<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Notifications\TaskApprovalNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Notification;

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

//      get all data

//      add to db with first or create
//        - add task to db
        $task = self::createTask($request);

//        - add status to status table
        self::createTaskStatus($task);

//      process after data has been add to db
//        - send notification for approval - if a part, repair, or above preapproval amount
//                - link will have to redirect to a page for customer to approve
//                - need notification message to send
        self::approveTask($task);

//          - can start as notification to contact shawn
//          - future will have the customer go on the app
//          - auto approval for items below a certain amount

//      redirect back or to another page
//        - redirect to customer page
        return Inertia::render('ServiceStops/Index', []);
    }

    private function createTask(Request $request)
    {
        return Task::firstOrCreate([
            'customer_id' => $request->customer_id,
            'description' => $request->description,
            'type' => $request->type,
            'status' => $request->status
        ]);
    }

    private function createTaskStatus($task)
    {
        return TaskStatus::firstOrCreate([
            'task_id' => $task->id,
            'status' => $task->status
        ]);
    }

    private function approveTask($task)
    {
//        send notification to customer if new part or repair
//          - create new notification
        Notification::route('vonage', '14807034902')
            ->notify(new TaskApprovalNotification('approve this task'));
    }
}
