<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Address;
use App\Models\ScpInvoiceItem;
use App\Models\Subcontractor;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskStatus;
use App\Notifications\GenericNotification;
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


    public function display()
    {
        $tasks = Task::where('status', "pickedUp")->orWhere('status', "approved")->get();

        $servicemen = User::where('type', 'serviceman')->where('active', 1)->get();

        $finalTaskArray = [];

        foreach ($tasks as $task) {

            if ($task->assigned === 2) {
                $name = '';
                $customer = Customer::find($task->customer_id);
                foreach ($servicemen as $sm) {
                    if ($task->assigned === $sm->id) {
                        $name = $sm->name;
                    }
                }
                $task->customerName = $customer->first_name . " " . $customer->last_name;
                $task->assignedName = $name;
                array_push($finalTaskArray, $task);
            }
        }

        return Inertia::render('Task/DisplayTasks', [
            'tasks' => $finalTaskArray
        ]);

    }

    public function updateReconciledTaskStatuses(Request $request)
    {
        self::processReconciledTask($request->taskId, $request->taskStatuses['created'], 'created');
        self::processReconciledTask($request->taskId, $request->taskStatuses['approved'], 'approved');
        self::processReconciledTask($request->taskId, $request->taskStatuses['pickedUp'], 'pickedUp');
        self::processReconciledTask($request->taskId, $request->taskStatuses['completed'], 'completed');
        self::processReconciledTask($request->taskId, $request->taskStatuses['remove'], 'remove');
        self::processReconciledTask($request->taskId, $request->taskStatuses['diy'], 'diy');
        self::processReconciledTask($request->taskId, $request->taskStatuses['denied'], 'denied');
        self::processReconciledTask($request->taskId, $request->taskStatuses['invoiced'], 'invoiced');
        self::processReconciledTask($request->taskId, $request->taskStatuses['paid'], 'paid');
    }

    public function processReconciledTask($taskId, $taskStatusExists, $taskStatus)
    {

        $taskStatuses = TaskStatus::where('task_id', $taskId)->get();
        if ($taskStatusExists) {
            $exists = false;
            foreach ($taskStatuses as $status) {
                if ($status->status === $taskStatus) {
                    $exists = true;
                }
            }

            $carbonDate = Carbon::now();
            $mysqlDate = $carbonDate->format('Y-m-d');

            if (!$exists) {
                $ts = new TaskStatus(
                    [
                        'task_id' => $taskId,
                        'status' => $taskStatus,
                        'status_date' => $mysqlDate,
                        'created_at' => $mysqlDate,
                        'status_creator' => 2,
                    ]
                );
                $ts->save();
            }

            $task = Task::find($taskId);
            $task->status = $taskStatus;
            $task->save();

        } else {
//            if created exists then remove it from the database
            foreach ($taskStatuses as $status) {
                if ($status->status === $taskStatus) {
                    $statusId = $status->id;
                    $status = TaskStatus::find($statusId);
                    $status->delete();
                }
            }
        }

    }

    public function reconcilePickedUpTasks()
    {
        $tasks = Task::select(
            ['id', 'customer_id', 'assigned',
                'created_at', 'description', 'status', 'sent',
                'type', 'price', 'address_id'])
            ->where('status', 'pickedUp')
            ->where('created_at', '>', '2024-05-01 00:00:00')
            ->get();

        foreach ($tasks as $task) {
            $customer = Customer::find($task->customer_id);
            $taskAssignedServiceman = User::find($task->assigned);
            $task->name = $taskAssignedServiceman->name;
            $task->first_name = $customer->first_name;
            $task->last_name = $customer->last_name;
            $task->phone_number = $customer->phone_number;
        }

        $servicemen = User::where('active', 1)->where('type', 'serviceman')->get();

        return Inertia::render('Task/ReconcilePickedUp', [
            'tasks' => $tasks,
            'servicemen' => $servicemen
        ]);
    }

    public function reconcileCreatedTasks()
    {
        $tasks = Task::select(
            ['id', 'customer_id', 'assigned',
                'created_at', 'description', 'status', 'sent',
                'type', 'price', 'address_id'])
            ->where('status', 'created')
            ->where('assigned', 3)
            ->where('created_at', '>', '2024-05-01 00:00:00')
            ->get();

        foreach ($tasks as $task) {
            $customer = Customer::find($task->customer_id);
            $taskAssignedServiceman = User::find($task->assigned);
            $task->name = $taskAssignedServiceman->name;
            $task->first_name = $customer->first_name;
            $task->last_name = $customer->last_name;
            $task->phone_number = $customer->phone_number;
        }

        $servicemen = User::where('active', 1)->where('type', 'serviceman')->get();

        return Inertia::render('Task/ReconcileCreated', [
            'tasks' => $tasks,
            'servicemen' => $servicemen
        ]);
    }

    public function reconcile()
    {

//        $tasks = Task::with(['task_statuses', 'customer', 'address'])  // Eager load the task statuses
//        ->orderBy('customer_id')  // Order by customer_id
//        ->get();

//        $tasks = Task::select(
//            'id', 'customer_id', 'assigned', 'created_at',
//            'description', 'status', 'type', 'price', 'address_id'
//        )  // Selecting specific fields from tasks
//        ->with([
//            'task_statuses',  // Selecting specific fields from task statuses
//            'customer:id,first_name,last_name',             // Selecting specific fields from customers
//            'address:id,address_line_1',             // Selecting specific fields from customers
//        ])
//            ->orderBy('customer_id')
//            ->get();

//        $tasks = Task::select(
//            'id', 'customer_id', 'assigned', 'created_at',
//            'description', 'status', 'type', 'price', 'address_id'
//        )  // Selecting specific fields from tasks
//        ->with([
//            'task_statuses',  // Selecting specific fields from task statuses
//            'customer:id,first_name,last_name',             // Selecting specific fields from customers
//            'address:id,address_line_1',             // Selecting specific fields from customers
//        ])
//            ->orderBy('customer_id')
//            ->get();


//        STATUS === APPROVED
//        ==============================
        $tasks = Task::select(['id', 'customer_id', 'assigned', 'created_at', 'description', 'status', 'type', 'price', 'address_id'])

//            ======= All tasks that have been created but have not been sent for approval ====
            ->where('status', 'completed')
//            ->where('assigned', 10)
            ->where('updated_at', '<>', '2024-10-01 00:00:00')
            ->where('type', '<>', 'todo')
//            ->where('sent', 0)

//            ======= All tasks that have been created and sent for approval but have not been responded too =====
//            ======= Need to call these people =====
//            ->where('status', 'created')
//            ->where('sent', 1)

//            ======= All completed tasks so that I dont bill for something that has been paid for already
//            ->where('status', 'completed')
//            ->where('type', '<>', 'todo')


            ->get();

//        dd($tasks);

//        ->where('created_at', '>', '2024-05-01 00:00:00')

//        ALL TASKS IN THE CURRENT MONTH
//        ==============================
//        $tasks = Task::select(['id', 'customer_id', 'assigned', 'created_at', 'description', 'status', 'type', 'price', 'address_id'])
//            ->where('created_at', '>', '2024-06-01 00:00:00')
//            ->where('status', '<>', 'denied')
//            ->get();


//        ALL TASKS FOR A SPECIFIC CUSTOMER
//        ==============================
//        $tasks = Task::select(['id', 'customer_id', 'assigned', 'created_at', 'description', 'status', 'type', 'price', 'address_id'])
//            ->where('customer_id', 278)
//            ->get();

//        ALL PARTS AND REPAIRS THAT HAVE BEEN PICKED UP, APPROVED, OR CREATED
//        ==============================
//        $tasks = Task::select(['id', 'customer_id', 'assigned', 'created_at', 'description', 'status', 'type', 'price', 'address_id'])
//            ->where(function ($query) {
//                $query->where('status', 'approved')
//                    ->orWhere('status', 'pickedUp')
//                    ->orWhere('status', 'created')
//                    ->orWhere('status', 'completed');
//            })->where(function ($query) {
//                $query->where('type', 'part')
//                    ->orWhere('type', 'repair');
//            })
//            ->where('created_at', '>', '2024-01-01 00:00:00')
//            ->orderBy('customer_id')
//            ->get();

//        $names = [];
//        $tasksWithAttributes = self::getTasksFromSpecificCustomersByLastName($tasks, $names);

        $tasksWithAttributes = self::getTasksAsAnArray($tasks);


//        dd($tsks);

        //        dd($tsks);

        usort($tasksWithAttributes, function ($a, $b) {
            return $a[1]['last_name'] <=> $b[1]['last_name'];
        });


        return Inertia::render('Task/Reconcile', [
            'tasks' => $tasksWithAttributes
        ]);

    }

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

    private function getTasksAsAnArray($tasks)
    {
        $tasksWithAttributes = [];

        $t = [];

        foreach ($tasks as $task) {
            $customer = Customer::find($task->customer_id);

            $address = Address::find($task->address_id);
            $task_statuses = TaskStatus::where('task_id', $task->id)->get();

            $t['id'] = $task->id;
            $t['customer_id'] = $task->customer_id;
            $t['created_at'] = $task->created_at;
            $t['description'] = $task->description;
            $t['assigned'] = $task->assigned;
            $t['status'] = $task->status;
            $t['type'] = $task->type;
            $t['price'] = $task->price;
            $t['address_id'] = $task->address_id;
            $t['deleted'] = false;

            $c['id'] = $customer->id;
            $c['first_name'] = $customer->first_name;
            $c['last_name'] = $customer->last_name;

            $a['id'] = $address->id;
            $a['address_line_1'] = $address->address_line_1;


            $tsArray = [
                'created' => false,
                'approved' => false,
                'pickedUp' => false,
                'completed' => false,
                'remove' => false,
                'diy' => false,
                'denied' => false,
                'invoiced' => false,
                'paid' => false,
            ];

            foreach ($task_statuses as $status) {
                if ($status->status === 'created') $tsArray['created'] = true;
                if ($status->status === 'approved') $tsArray['approved'] = true;
                if ($status->status === 'pickedUp') $tsArray['pickedUp'] = true;
                if ($status->status === 'completed') $tsArray['completed'] = true;
                if ($status->status === 'remove') $tsArray['remove'] = true;
                if ($status->status === 'diy') $tsArray['diy'] = true;
                if ($status->status === 'denied') $tsArray['denied'] = true;
                if ($status->status === 'invoiced') $tsArray['invoiced'] = true;
                if ($status->status === 'paid') $tsArray['paid'] = true;
            }

            array_push($t, $tsArray);
            array_push($t, $c);
            array_push($t, $a);
            array_push($tasksWithAttributes, $t);

            $t = [];
            $c = [];
            $a = [];

        }

        return $tasksWithAttributes;
    }

    public function getTasksFromSpecificCustomersByLastName($tasks, $names)
    {
        $tsks = [];

        $t = [];

        foreach ($tasks as $task) {
            $customer = Customer::find($task->customer_id);

            foreach ($names as $name) {
                if ($customer->last_name === $name) {
                    $address = Address::find($task->address_id);
                    $task_statuses = TaskStatus::where('task_id', $task->id)->get();

                    $t['id'] = $task->id;
                    $t['customer_id'] = $task->customer_id;
                    $t['created_at'] = $task->created_at;
                    $t['description'] = $task->description;
                    $t['assigned'] = $task->assigned;
                    $t['status'] = $task->status;
                    $t['type'] = $task->type;
                    $t['price'] = $task->price;
                    $t['address_id'] = $task->address_id;
                    $t['deleted'] = false;

                    $c['id'] = $customer->id;
                    $c['first_name'] = $customer->first_name;
                    $c['last_name'] = $customer->last_name;

                    $a['id'] = $address->id;
                    $a['address_line_1'] = $address->address_line_1;


                    $tsArray = [
                        'created' => false,
                        'approved' => false,
                        'pickedUp' => false,
                        'completed' => false,
                        'remove' => false,
                        'diy' => false,
                        'denied' => false,
                        'invoiced' => false,
                        'paid' => false,
                    ];

                    foreach ($task_statuses as $status) {
                        if ($status->status === 'created') $tsArray['created'] = true;
                        if ($status->status === 'approved') $tsArray['approved'] = true;
                        if ($status->status === 'pickedUp') $tsArray['pickedUp'] = true;
                        if ($status->status === 'completed') $tsArray['completed'] = true;
                        if ($status->status === 'remove') $tsArray['remove'] = true;
                        if ($status->status === 'diy') $tsArray['diy'] = true;
                        if ($status->status === 'denied') $tsArray['denied'] = true;
                        if ($status->status === 'invoiced') $tsArray['invoiced'] = true;
                        if ($status->status === 'paid') $tsArray['paid'] = true;
                    }

                    array_push($t, $tsArray);
                    array_push($t, $c);
                    array_push($t, $a);
                    array_push($tsks, $t);

                    $t = [];
                    $c = [];
                    $a = [];
                }
            }
        }

        return $tsks;

    }

    public function customerTasks(Request $request)
    {

        is_null($request->addressId['id']) ? $address = $request->id : $address = $request->addressId['id'];

        $tasks = Task::allCustomerCreatedTasks($request->customerId, $address);
        $servicemen = User::where('active', 1)->where('type', 'serviceman')->get();

        return Inertia::render('Task/CustomerNeedsApproval', [
            'tasks' => $tasks,
            'servicemen' => $servicemen
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

    public function requestApprovalFromReconcile(Request $request)
    {
        self::getApprovalFromReconcile(
            $request["id"],
            $request["price"],
            $request["description"],
            $request["phone_number"]
        );
    }

    private function getApprovalFromReconcile($task_id, $price, $description, $phone_number)
    {
        $task = Task::find($task_id);

        //        send for approval if the task has not been verbally approved
        $message = "Please Reply\n==================\n\nKPS Pools needs to inform you about a necessary repair for your pool:\n\n" . $description . " for $" . $price . "\n\nWould you like for us to complete this for you?\n\nY$task->count for Yes\nN$task->count for No\n\nYou may also reach out to Shawn at 480.703.4902 or 480.622.6441. If you have any questions";

        self::sendforApproval($task, $phone_number, $message);

        $task->sent = true;
        $task->save();
    }

    public function requestApproval(Request $request)
    {
        $task = Task::find($request->task_id);

        //        send for approval if the task has not been verbally approved
        $message = "Please Reply\n==================\n\nKPS Pools needs to inform you about a necessary repair for your pool:\n\n" . $request->description . " for $" . $request->price . "\n\nWould you like for us to complete this for you?\n\nY$task->count for Yes\nN$task->count for No\n\nYou may also reach out to Shawn at 480.703.4902 or 480.622.6441. If you have any questions";

        self::sendforApproval($task, $request->phone_number, $message);

        $task->sent = true;
        $task->save();
    }


    public function getTaskItems(Request $request)
    {
        $scpItems = ScpInvoiceItem::select(['scp_invoice_item.description', 'scp_invoice_item.cost', 'scp_invoice_item.created_at'])
            ->join(DB::raw('(SELECT description, MAX(created_at) as latest_created_at FROM scp_invoice_item GROUP BY description) as latest_items'),
                function ($join) {
                    $join->on('scp_invoice_item.description', '=', 'latest_items.description')
                        ->on('scp_invoice_item.created_at', '=', 'latest_items.latest_created_at');
                })
            ->where('scp_invoice_item.description', 'like', "%$request->name%")
            ->orderBy('scp_invoice_item.created_at', 'desc')
            ->get();

        $items = [];

        foreach ($scpItems as $item) {
            $i = [
                'description' => $item->description,
                'price' => $item->cost,
                'type' => 'scpItem'
            ];
            $items[] = $i;
        }

        return $items;
    }


    public function changeStatus(Request $request)
    {
//        dd($request);
        $task = Task::find($request->task_id);

        if ($task === null) {
            $task = Task::find($request->id);
        }

        $task->status = $request->status;
        $task->save();
        self::addTaskStatus($task, $request->status);
    }

    public function changeType(Request $request)
    {
//        dd($request);
//        dd($request->id);

        is_null($request->task_id) ? $taskId = $request->id : $taskId = $request->task_id;

        $task = Task::find($taskId);

        if ($task === null) {
            $task = Task::find($request->id);
        }

        $task->type = $request->type;
        $task->save();
    }

    public function changeDescription(Request $request)
    {
//        dd($request->task_id);
//        dd($request->id);

        is_null($request->task_id) ? $taskId = $request->id : $taskId = $request->task_id;

        $task = Task::find($taskId);
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

    private function assignServicemanFromReconcile()
    {
//        "id" => 602
//      "customer_id" => 302
//      "assigned" => "Shawn"
//      "created_at" => "2024-05-09T10:14:35.000000Z"
//      "description" => "Replace skimmer baskets"
//      "status" => "created"
//      "sent" => 1
//      "type" => "part"
//      "price" => 1000
//      "address_id" => 303
//      "name" => "Jeremiah"
//      "first_name" => "Mike"
//      "last_name" => "Sherman"
//      "phone_number" => "14807034902"
    }

    public function assignServiceman(Request $request)
    {
//        dd($request);
//        is_null();


        $user = User::where('name', $request->assigned)->get();
//        dd($user[0]->id);

        $customer = Customer::find($request->customer_id);

        is_null($request->task_id) ? $taskId = $request['id'] : $taskId = $request->task_id;

//        dd($taskId);

        $task = Task::find($taskId);

        $task->assigned = $user[0]->id;
        $task->save();
        if (Auth::user()->getAuthIdentifier() !== $user[0]->id) {
            Notification::route('vonage', $user[0]->phone_number)->notify(new GenericNotification(
                "You were assigned a Task::\n$customer->first_name $customer->last_name\n$request->description\n" . env('APP_URL') . "/customers/show/" . $customer->id
            ));
        }
    }

    private function deleteTheTask($taskId)
    {
        $task = Task::find($taskId);

        $task->delete();

        $taskStatuses = TaskStatus::where('task_id', $taskId)->get();

        foreach ($taskStatuses as $ts) {
            $ts->delete();
        }
    }

    public function deleteItemFromReconcile(Request $request)
    {
        self::deleteTheTask($request->task_id["id"]);
    }

    public function deleteItem(Request $request)
    {
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

    public function repairPage()
    {
        $tasks = Task::where('status', 'approved')->get();
        return Inertia::render('Task/RepairPage', [
            'tasks' => $tasks
        ]);
    }

    public function updatePrice(Request $request)
    {
//        dd($request);

        is_null($request->task_id) ? $taskId = $request->id : $taskId = $request->task_id;

        $task = Task::find($taskId);
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
    public function create(Address $address): Response
    {
//        dd($address);

//        $address = DB::select('Select * from addresses where customer_id = '
//            . $customer->id);
//
        $users = User::select(['id', 'name'])->where('type', '=', 'serviceman')->where('active', '=', true)->get();

//        dd($customer->id);

        $customer = Customer::find($address->customer_id);
        $subs = Subcontractor::select(['company_name'])->get();

        $subcontractors = [null];
        foreach ($subs as $sub) {
            $subcontractors[] = $sub->company_name;
        }

        return Inertia::render('Task/Create', [
            'addressId' => $address->id,
            'customerId' => $address->customer_id,
            'subcontractors' => $subcontractors,
            'customer' => $customer,
            'customerName' => $customer->last_name,
            'users' => $users,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {

//        dd($request);

        $subcontractor = Subcontractor::where('company_name', $request->subcontractor)->first();

        if (!is_null($subcontractor)) {
            $customer = Customer::find($request->customer_id);
            $address = Address::find($request->address_id);
            $customerUser = User::find($customer->user_id);
            strpos($customerUser->email, '.') != false ? $email = $customerUser->email : $email = 'Email has not been recorded';
//            Notification::route('vonage', '14806226441')->notify(new GenericNotification("KPS Pools has a job for you.
            Notification::route('vonage', $subcontractor->phone_number)->notify(new GenericNotification("KPS Pools has a job for you.
=====================
$request->description
=====================
Customer Info::
$customer->first_name $customer->last_name
$customer->phone_number
$email
$address->address_line_1 $address->city $address->zip
=====================
Please reach out to Shawn for any questions at 14807034902"));

            if ($subcontractor->company_name !== 'Edge Water Pools LLC') {

//            Notification::route('vonage', '14807034902')->notify(new GenericNotification("Skyline has been notified. They can be reached at:
                Notification::route('vonage', $customer->phone_number)->notify(new GenericNotification($subcontractor->company_name . " has been notified. They can be reached at:
=====================
$subcontractor->contact_name
$subcontractor->company_name
$subcontractor->phone_number
=====================
Please reach out to Shawn for any questions at 14807034902"));
            }

        } else {

            if (is_null($request->type)) {
                $request->type = 'part';
            }

            if ($request->type == 'todo') {
                $task = self::createTask($request);
                self::addTaskStatus($task, 'created',);
                self::addStatus($task, 'pickedUp');
                self::addTaskStatus($task, 'pickedUp',);
                $task->assigned = $request->todoAssignee;
                $task->save();
                $user = User::find($request->todoAssignee);
                $customer = Customer::find($request->customer_id);
                if (Auth::user()->getAuthIdentifier() !== $user->id) {
                    Notification::route('vonage', $user->phone_number)->notify(new GenericNotification(
                        "You were assigned a Task::\n$customer->first_name $customer->last_name\n$request->description\n" . env('APP_URL') . "/customers/show/" . $request->address_id
                    ));
                }
            } else {
                $task = self::createTask($request);

                self::addTaskStatus($task, 'created',);


//                TODO:: should reflect past price for the specific customer
//                TODO:: should reflect paid prices because those are prices that customers have approved
//                TODO:: associate the correct scp item with the task

//                if ($taskItem->isEmpty()) {

                if (!is_null($request->price)) {
                    $task->price = $request->price * 1.38 * $request->quantity;
                    $task->save();
                }
            }

        }
        return Redirect::route('customers.show', $request->address_id);

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

//        dd($request);

        $task = Task::firstOrCreate([
            'address_id' => $request->address_id,
            'customer_id' => $request->customer_id,
            'description' => $request->description,
            'assigned' => $request->assigned,
            'type' => $request->type,
            'price' => 0,
            'status' => 'created',
            'count' => 0
        ]);

        $tasks = Task::where('customer_id', $request->customer_id)
            ->orderBy('count', 'desc')
            ->get();

        $task->count = $tasks[0]->count + 1;
        $task->save();

        return $task;
    }

    private function addTaskStatus($task, $status, $statusDate = null)
    {
        if (is_null($statusDate)) {
            $date = Carbon::now();
            $statusDate = $date->format('Y-m-d H:i:s');
        }

        $taskStatus = new TaskStatus();
        $taskStatus->status_creator = Auth::user()->id;
        $taskStatus->task_id = $task->id;
        $taskStatus->status = $status;
        $taskStatus->status_date = $statusDate;

        $taskStatus->save();

        return $taskStatus;
    }

    private function sendforApproval($task, $phoneNumber, $message = null)
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
            Notification::route('vonage', $phoneNumber)
                ->notify(new TaskApprovalNotification($message));
        }
    }
}
