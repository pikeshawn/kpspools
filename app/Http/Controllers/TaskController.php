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

        $tasks = Task::allIncompleteTasks();

        return Inertia::render('Task/Index', [
            'tasks' => $tasks,
        ]);
    }

    public function tasksNeedsApproval()
    {

//        dd(Auth::user());

//        dd(User::isAdmin());

//            dd('isAdmin');
        $tasks = Task::allCreatedTasks();

//        return $tasks;

        return Inertia::render('Task/NeedsApproval', [
            'tasks' => $tasks,
        ]);
    }

    public function repairsAndPartsList()
    {

//        dd(Auth::user());

//        dd(User::isAdmin());

//            dd('isAdmin');
        $tasks = Task::allTasksForPoolGuy();

//        return $tasks;

        return Inertia::render('Task/RepairsAndPartsList', [
            'tasks' => $tasks,
        ]);
    }

    public function requestApproval(Request $request)
    {

        $task = Task::find($request->task_id);

        //        send for approval if the task has not been verbally approved
        $message = "Do Not Reply\n==================\n\nKPS Pools wants to inform you that a part / repair is required at your pool:\n\n" . $request->description . " for " . $request->price . "$\n\nPlease call or text Shawn to approve, deny, or take care of issue yourself at either number:\n\n480.703.4902\n480.622.6441";

        self::sendforApproval($task, $message);

        $task->sent = true;
        $task->save();
    }

    public function changeStatus(Request $request)
    {
//        dd($request);
        $task = Task::find($request->task_id);
        $task->status = $request->status;
        $task->save();
        self::addTaskStatus($task, $request->status);
    }
    public function changeDescription(Request $request)
    {
//        dd($request);
        $task = Task::find($request->task_id);
        $task->description = $request->description;
        $task->save();
        self::addTaskStatus($task, $request->status);
    }

    public function approveItem(Request $request)
    {
        //        send for approval if the task has not been verbally approved

        $task = Task::find($request->task_id);

        if ($request->approved) {
            self::addTaskStatus($task, 'approved');
            self::addStatus($task, 'approved');
        } else if (!$request->approved) {
            self::addStatus($task, 'created');
        }
    }

    public function assignServiceman(Request $request)
    {
//        dd($request);
        $user = User::where('name', $request->assigned)->get();
//        dd($user[0]->id);

        $task = Task::find($request->task_id);
        $task->assigned = $user[0]->id;
        $task->save();
    }

    public function deleteItem(Request $request)
    {

        $task = null;

        if ($request->task_id) {
            $task = Task::find($request->task_id);
        } else if ($request->id) {
            $task = Task::find($request->id);
        }

        $task->delete();

        $taskStatuses = TaskStatus::where('task_id', $request->task_id)->get();

        foreach ($taskStatuses as $ts) {
            $ts->delete();
        }

    }

    public function updatePrice(Request $request)
    {
//        dd($request);
        $task = Task::find($request->task_id);
        $task->price = $request->price;
        $task->save();
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
        $task = Task::find($request->id);
        self::addStatus($task, 'completed');
        self::addTaskStatus($task, 'completed');

    }

    public function notCompleted(Request $request)
    {
        $task = Task::find($request->id);
        self::addStatus($task, 'pickedUp');
        self::addTaskStatus($task, 'pickedUp');
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
            'price' => 0,
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
        $taskStatus->status = $status;
        $taskStatus->status_date = $statusDate;

        $taskStatus->save();

        return $taskStatus;
    }

    private function sendforApproval($task, $message = null)
    {
//        send notification to customer if new part or repair
//          - create new notification
        $repair = false;
        $part = false;
        $task->type == 'repair' ? $repair = true : $repair = false;
        $task->type == 'part' ? $part = true : $part = false;

        if ($message == null) {
            if ($repair) {
                $message = "We wanted to notify you that your pool needs to have a repair done:: $task->description. Please text or call Shawn to approve or deny the task at 480-703-4902.";
            }

            if ($part) {
                $message = "We wanted to notify you that your pool needs a part:: $task->description. Please text or call Shawn to approve or deny the part at 480-703-4902.";
            }
        }

//        TODO:: change number to make this dynamic to the customer
        if ($repair || $part) {
            Notification::route('vonage', '14807034902')
                ->notify(new TaskApprovalNotification($message));
        }
    }
}
