<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskStatus;
use App\Notifications\TaskApprovalNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Notification;
use function PHPUnit\Framework\isNull;

class TaskController extends Controller
{


    public function index()
    {

//        dd(Auth::user());

//        dd(User::isAdmin());

//            dd('isAdmin');
            $tasks = Task::allIncompleteTasks();

//        return $tasks;

        return Inertia::render('Task/Index', [
            'tasks' => $tasks,
        ]);
    }

    public function pickedUp(Request $request)
    {
//        dd($request->all());
        foreach ($request->all() as $taskItem) {
//            dd($key);
            if ($taskItem['pickedUp']) {
                $task = Task::find($taskItem["task_id"]);
                self::addStatus($task, 'pickedUp');
                self::addTaskStatus($task, 'pickedUp');
            }
        }

    }

    public function completed(Request $request)
    {
//        dd($request->all());
        foreach ($request->all() as $taskItem) {
//            dd($key);
            if ($taskItem['completed']) {
                $task = Task::find($taskItem["id"]);
                self::addStatus($task, 'completed');
                self::addTaskStatus($task, 'completed');
            }
        }

    }

    public function notCompleted(Request $request)
    {
//        dd($request->all());
        foreach ($request->all() as $taskItem) {
//            dd($key);
            if ($taskItem['completed']) {
                $task = Task::find($taskItem["id"]);
                self::addStatus($task, 'pickedUp');
                self::addTaskStatus($task, 'pickedUp');
                $taskStatus = TaskStatus::where('status', 'completed')->where('task_id', $task->id);
                $taskStatus->delete();
            }
        }

    }

    public function remove(Request $request)
    {
//        dd($request->all());
        foreach ($request->all() as $taskItem) {
//            dd($key);
            if ($taskItem['pickedUp']) {
                $task = Task::find($taskItem["task_id"]);
                self::addStatus($task, 'approved');
                self::addTaskStatus($task, 'remove');
                $taskStatus = TaskStatus::where('status', 'pickedUp')->where('task_id', $task->id);
                $taskStatus->delete();
            }
        }

    }

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

    public function store(Request $request): RedirectResponse
    {

//      get all data
//        dd($request);

//      add to db with first or create
//        - add task to db
        $task = self::createTask($request);

//        - add status to status table
        self::addTaskStatus($task, 'created',);
//        if task has been verbally approved then add the approved status
        if ($request->approval) {
            self::addTaskStatus($task, 'approved', $request->approvedDate);
            self::addApprovedStatusToTaskTable($task);
        }

//      process after data has been add to db
//        - send notification for approval - if a part, repair, or above preapproval amount
//                - link will have to redirect to a page for customer to approve
//                - need notification message to send

//        send for approval if the task has not been verbally approved
        if (!$request->approval) {
            self::sendforApproval($task);
        }

//          - can start as notification to contact shawn
//          - future will have the customer go on the app
//          - auto approval for items below a certain amount

//      redirect back or to another page
//        - redirect to customer page
        return Redirect::route('customers.show', $task->customer_id);

    }

    private function addApprovedStatusToTaskTable($task)
    {
        $task->status = 'approved';
        $task->save();
    }

    private function addStatus($task, $status)
    {
        $task->status = $status;
        $task->save();
    }

    private function createTask(Request $request)
    {
        return Task::firstOrCreate([
            'customer_id' => $request->customer_id,
            'description' => $request->description,
            'assigned' => $request->assigned,
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

        $taskStatus = new TaskStatus();
        $taskStatus->task_id = $task->id;
        $taskStatus->status = $task->status;
        $taskStatus->status_date = $statusDate;

        $taskStatus->save();

        return $taskStatus;
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
