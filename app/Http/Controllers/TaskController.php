<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Notifications\TaskApprovalNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Notification;
use function PHPUnit\Framework\isNull;

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
//        dd($request);

//      add to db with first or create
//        - add task to db
        $task = self::createTask($request);

//        - add status to status table
        self::addTaskStatus($task, 'created');
//        if task has been verbally approved then add the approved status
        if ($request->approval) {
            self::addTaskStatus($task, 'approved');
        }

//      process after data has been add to db
//        - send notification for approval - if a part, repair, or above preapproval amount
//                - link will have to redirect to a page for customer to approve
//                - need notification message to send

//        send for approval if the task has not been verbally approved
        if(!$request->approval){
            self::sendforApproval($task);
        }

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
            'status' => 'created'
        ]);
    }

    private function addTaskStatus($task, $status, $statusDate = null)
    {
        if (is_null($statusDate)) {
            $date = Carbon::now();
            $statusDate = $date->format('Y-m-d H:i:s');
        }
        return TaskStatus::firstOrCreate([
            'task_id' => $task->id,
            'status' => $status,
            'status_date' => $statusDate
        ]);
    }

    private function sendforApproval($task)
    {
//        send notification to customer if new part or repair
//          - create new notification
        $repair = false;
        $part = false;
        $task->type == 'repair' ? $repair = true : $repair = false;
        $task->type == 'part' ? $part = true : $part = false;

        if ($repair) {
            $message = "We wanted to notify you that your pool needs to have a repair done:: $task->description. Please text or call Shawn to approve or deny the task at 480-703-4902.";
        }

        if ($part) {
            $message = "We wanted to notify you that your pool needs a part:: $task->description. Please text or call Shawn to approve or deny the part at 480-703-4902.";
        }

//        TODO:: change number to make this dynamic to the customer
        if ($repair || $part) {
            Notification::route('vonage', '14807034902')
                ->notify(new TaskApprovalNotification($message));
        }
    }
}
