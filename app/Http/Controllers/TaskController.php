<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\TaskImage;
use App\Models\EmployeePayment;
use App\Models\UserJobRate;
use App\Models\JobType;
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
use function Laravel\Prompts\select;
use function PHPUnit\Framework\isNull;

class TaskController extends Controller
{

    public function getTasksFromSelectedCriteria(Request $request)
    {

        if (!Auth::user()->is_admin) {
            $request->serviceman = Auth::user()->id;
        }

        $query = Task::when($request->customer !== 'all', function ($q) use ($request) {
            return $q->where('customer_id', $request->customer_id);
        })
            ->when($request->address !== 'all', function ($q) use ($request) {
                return $q->where('address_id', $request->address_id);
            })
            ->when($request->status !== 'all', function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->when($request->status === 'all', function ($q) use ($request) {
                return $q->whereIn('status', ['created',
                    'approved',
                    'pickedUp',
                    'completed']);
            })
            ->when($request->type !== 'all', function ($q) use ($request) {
                if ($request->type === 'repair' || $request->type === 'part') {
                    return $q->whereIn('type', ['repair', 'part']);
                } else {
                    return $q->where('type', 'todo');
                }
            })
            ->when($request->serviceman !== 'all', function ($q) use ($request) {
                return $q->where('assigned', $request->serviceman);
            })
            ->when($request->sent !== 'both', function ($q) use ($request) {
                return $q->where('sent', $request->sent);
            })
            ->select([
                'customer_id as customer_id',
                'address_id as address_id',
                'scp_id as product_number',
                'id as task_id',
                'rate as sub_rate',
                'description',
                'type',
                'status',
                'assigned',
                'price',
                'sent',
                DB::raw('false as deleted')
            ]);

        $tasks = $query->get();

        foreach ($tasks as $task) {

            $jt = JobType::where('name', $task->description)->first();

            if (!is_null($jt)) {
                $task->jobType = true;
            } else {
                $task->jobType = false;
            }

            $customer = Customer::find($task->customer_id);
            $address = Address::find($task->address_id);
            $task->first_name = $customer->first_name;
            $task->last_name = $customer->last_name;
            $task->phone_number = $customer->phone_number;
            $task->address = $address->address_line_1 . ', ' . $address->city . ' ' . $address->zip;
        }

        return $tasks;
    }

    public function getTasks(Request $request)
    {

        // Extract input parameters
        $sub = $request->input('sub');
        $subName = $request->input('subName');
        $status = $request->input('status');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Initialize the task array
        $taskArray = [
            "name" => $subName,
            "id" => $sub,
            "tasks" => []
        ];

        // Fetch tasks from the tasks table based on the filters
        $tasks = DB::table('tasks')
            ->where('assigned', $sub)
            ->where('status', $status)
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->get();

        // Loop through each task and retrieve related data
        foreach ($tasks as $task) {
            // Fetch statuses related to the task
            $statuses = DB::table('task_statuses')
                ->where('task_id', $task->id)
                ->select('status', 'status_creator', 'status_date', 'created_at', 'updated_at')
                ->get();

            // Fetch address information
            $address = DB::table('addresses')
                ->where('id', $task->address_id)
                ->select('id', 'customer_id', 'address_line_1', 'city', 'zip')
                ->first();

            // Fetch customer information
            $customer = null;
            if ($address) {
                $customerData = DB::table('customers')
                    ->where('id', $address->customer_id)
                    ->select('first_name', 'last_name')
                    ->first();

                if ($customerData) {
                    $customer = [
                        "name" => $customerData->first_name . " " . $customerData->last_name,
                        "address" => "{$address->address_line_1}, {$address->city} {$address->zip}",
                        "link" => env("APP_URL") . "/customers/show/" . $address->id
                    ];
                }
            }

            // Create sub array
            $subData = [
                "beenPaid" => $task->been_paid,
                "rate" => $task->rate
            ];

            // Add task to task array
            $taskArray["tasks"][] = [
                "id" => $task->id,
                "description" => $task->description,
                "status" => $task->status,
                "type" => $task->type,
                "price" => $task->price,
                "quantity" => $task->quantity,
                "approvalSent" => $task->sent,
                "created_at" => $task->created_at,
                "updated_at" => $task->updated_at,
                "statuses" => $statuses,
                "sub" => $subData,
                "customer" => $customer
            ];
        }

//        dd($taskArray);

        // Return the task array as JSON
//        return response()->json(["subTasks" => [$taskArray]]);
        return $taskArray;
    }

    public function subTasks()
    {

//        $statuses = [];

        $subs = User::where('type', 'serviceman')->where('active', true)->get();

        return Inertia::render('Task/SubTasks', [
            'statuses' => ["created", "approved", "completed", "diy", "denied"],
            'subs' => $subs
        ]);


    }

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
        $servicemen = User::select(["id", "name"])->where('active', 1)->where('type', 'serviceman')->get();


        if (Auth::user()->is_admin) {
            $created = Task::where('status', 'created')->count();
            $createdAndSent = Task::where('status', 'created')->where('sent', true)->count();
            $approved = Task::where('status', 'approved')->count();
            $pickedUp = Task::where('status', 'pickedUp')->count();
            $completed = Task::where('status', 'completed')->count();
            $invoiced = Task::where('status', 'invoiced')->count();
            $paid = Task::where('status', 'paid')->count();
            $removed = Task::where('status', 'removed')->count();
            $denied = Task::where('status', 'denied')->count();
            $diy = Task::where('status', 'diy')->count();
            $repair = Task::where('type', 'repair')->count();
            $todo = Task::where('type', 'todo')->count();
            $part = Task::where('type', 'part')->count();
        } else {
            $loggedInUserId = Auth::user()->getAuthIdentifier();
            $created = Task::where('status', 'created')->where('assigned', $loggedInUserId)->count();
            $createdAndSent = Task::where('status', 'created')->where('assigned', $loggedInUserId)->where('sent', true)->count();
            $approved = Task::where('status', 'approved')->where('assigned', $loggedInUserId)->count();
            $pickedUp = Task::where('status', 'pickedUp')->where('assigned', $loggedInUserId)->count();
            $completed = Task::where('status', 'completed')->where('assigned', $loggedInUserId)->count();
            $invoiced = Task::where('status', 'invoiced')->where('assigned', $loggedInUserId)->count();
            $paid = Task::where('status', 'paid')->where('assigned', $loggedInUserId)->count();
            $removed = Task::where('status', 'removed')->where('assigned', $loggedInUserId)->count();
            $denied = Task::where('status', 'denied')->where('assigned', $loggedInUserId)->count();
            $diy = Task::where('status', 'diy')->where('assigned', $loggedInUserId)->count();
            $repair = Task::where('type', 'repair')->where('assigned', $loggedInUserId)->count();
            $todo = Task::where('type', 'todo')->where('assigned', $loggedInUserId)->count();
            $part = Task::where('type', 'part')->where('assigned', $loggedInUserId)->count();
        }


        return Inertia::render('Task/View', [
            'servicemen' => $servicemen,
            'created' => $created,
            'createdAndSent' => $createdAndSent,
            'approved' => $approved,
            'pickedUp' => $pickedUp,
            'completed' => $completed,
            'invoiced' => $invoiced,
            'paid' => $paid,
            'removed' => $removed,
            'denied' => $denied,
            'diy' => $diy,
            'repair' => $repair,
            'todo' => $todo,
            'part' => $part,
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
            'servicemen' => $servicemen,
            'addressId' => $request->addressId['id']
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

    public function sendTripMessage(Request $request)
    {
        dd($request);
    }

    public function getUserRate(Request $request)
    {
        // Validate the request parameters
        $request->validate([
            'serviceman' => 'required|integer',
            'description' => 'required|string'
        ]);

        // Find job type ID based on description
        $jobType = DB::table('job_types')
            ->where('name', $request->description)
            ->select('id')
            ->first();

        if (!$jobType) {
            return response()->json(['message' => 'Job type not found', 'rate_paid' => 0], 404);
        }

        // Fetch the repair rate from user_job_rates
        $rate = DB::table('user_job_rates')
            ->where('job_type_id', $jobType->id)
            ->where('user_id', $request->serviceman)
            ->value('rate_paid');

        return response()->json(['rate_paid' => $rate ?? 0]);
    }


    public function getTaskItems(Request $request)
    {
        // Fetch SCP Invoice Items matching the description
        $scpItems = ScpInvoiceItem::select([
            'model_num',
            'description',
            'cost',
            DB::raw('MAX(created_at) as latest_created_at'),
            'product_number'
        ])
            ->whereRaw("LOWER(description) LIKE LOWER(?)", ["%{$request->name}%"])
            ->groupBy('description', 'cost', 'product_number', 'model_num')
            ->orderBy('latest_created_at', 'desc')
            ->get();

        // Fetch Task Items matching the description
        $tasks = DB::table('job_types')
            ->select(['id', 'name', 'rate_charged'])
            ->whereRaw("LOWER(name) LIKE LOWER(?)", ["%{$request->name}%"])
            ->orderBy('name', 'asc')
            ->get();

        $items = [];

        // Process SCP Items
        foreach ($scpItems as $item) {
            $items[] = [
                'jobTypeId' => null,
                'modelNum' =>  $item->model_num,
                'description' => $item->description,
                'price' => $item->cost,
                'product_number' => $item->product_number,
                'repairmanRate' => null,
                'jobRate' => null,
                'type' => 'scpItem'
            ];
        }

        // Process Task Items
        foreach ($tasks as $item) {

            $jt = JobType::where('name', $item->name)->first();

            if (is_null($jt)) {
                $job_rate = 0;
                $repairman_rate = 0;
            } else {
                $job_rate = $jt->rate_charged;
                $repairman_rate = $jt->sub_rate;
            }

            $items[] = [
                'jobTypeId' => $item->id,
                'description' => $item->name,
                'price' => $item->rate_charged,
                'product_number' => null,
                'repairmanRate' => $repairman_rate,
                'jobRate' => $job_rate,
                'type' => 'taskItem'
            ];
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

        $customer = Customer::find($request->customer_id);
        $address = Address::find($request->address_id);
        $user = User::find($request->assigned);

        if ($request->status === 'approved') {
            Task::sendApprovalMessage($task, $customer, $user->phone_number, $address);
        } else if ($request->status === 'completed') {
            EmployeePayment::addRepairEntry($request->assigned, $task->id, $request->sub_rate);
            Task::sendCompletedMessage($task, $customer, '14807034902', $address, $user->name);
        } else
            self::addTaskStatus($task, $request->status);
    }

    public function changeType(Request $request)
    {
        is_null($request->task_id) ? $taskId = $request->id : $taskId = $request->task_id;

        $task = Task::find($taskId);

        if ($task === null) {
            $task = Task::find($request->id);
        }

        $task->type = $request->type;
        $task->save();
    }

    public function changeSubRate(Request $request)
    {
        is_null($request->task_id) ? $taskId = $request->id : $taskId = $request->task_id;

        $task = Task::find($taskId);

        if ($task === null) {
            $task = Task::find($request->id);
        }

        $task->rate = $request->sub_rate;
        $task->save();

        $ep = EmployeePayment::where('task_id', $request->task_id)->first();

        if (!is_null($ep)) {
            $ep->rate = $request->sub_rate;
            $ep->save();
        }

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

    public function changeProductNumber(Request $request)
    {

        $request->product_number ? $scpId = $request->product_number : $scpId = $request->scp_id;

        if ($scpId) {
            is_null($request->task_id) ? $taskId = $request->id : $taskId = $request->task_id;
            $task = Task::find($taskId);
            $task->scp_id = $scpId;
            $task->save();
        }
    }

    public function approveItem(Request $request)
    {
        //        send for approval if the task has not been verbally approved

        $task = Task::find($request->task_id);


        if ($request->approved) {
            self::addTaskStatus($task, 'approved');
            self::addStatus($task, 'approved');

//            Task::sendApprovalMessage($task, $customer, Auth::user()->phone_number, $address);

        } else if (!$request->approved) {
            self::addStatus($task, 'created');
        }
    }

    public function assignServiceman(Request $request)
    {

        $user = User::find($request->assigned);
        $customer = Customer::find($request->customer_id);
        is_null($request->task_id) ? $taskId = $request['id'] : $taskId = $request->task_id;
        $task = Task::find($taskId);
        $task->assigned = $request->assigned;
        $task->save();

        $ep = EmployeePayment::where('task_id', $request->task_id)->first();
        if (is_null($ep)) {
            EmployeePayment::addRepairEntry($request->assigned, $task->id, $request->sub_rate);
        } else {
            $ep->serviceman_id = $request->assigned;
        }

        if (Auth::user()->getAuthIdentifier() !== $user->id) {
            Notification::route('vonage', $user->phone_number)->notify(new GenericNotification(
                "You were assigned a Task::\n$customer->first_name $customer->last_name\n$request->description\n" . env('APP_URL') . "/customers/show/" . $task->address_id
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
        $ep = EmployeePayment::where('task_id', $task->id)->first();
        if (!is_null($ep)) {
            $ep->delete();
        }

        $taskStatuses = TaskStatus::where('task_id', $request->task_id)->get();

        foreach ($taskStatuses as $ts) {
            $ts->delete();
        }

    }

    public function addJobType(Request $request)
    {

        if ($request->addJobType) {
            $jt = JobType::where('name', $request->item['description'])->first();
            if (is_null($jt)) {
                $jt = new JobType([
                    'name' => $request->item['description'],
                    'rate_charged' => $request->item['price']
                ]);
                $jt->save();
            }
        } else {
            $jt = JobType::where('name', $request->item['description'])->first();
            if (!is_null($jt)) {
                $jt->delete();
            }
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

        $jt = JobType::where('name', $request->description)->first();

        if (is_null($jt)) {
            $jt = new JobType([
                'name' => $request->description,
                'rate_charged' => $request->price
            ]);
            $jt->save();
        }

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

        // Get the authenticated user
        $user = Auth::user();

        // Ensure the user exists
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($task->type === 'repair' || $task->type === 'part') {
            // Insert record into employee_payments table
            EmployeePayment::create([
                'serviceman_id' => $user->id,
                'service_stop_id' => null,
                'paycheck_id' => null,
                'task_id' => $task->id,
                'rate' => $task->rate, // Fetching repair_rate from Users table
                'status' => 'pending'
            ]);
        }



        $customer = Customer::find($task->customer_id);
        $address = Customer::find($task->address_id);
        $assignee = Auth::user()->name;

        Task::sendCompletedMessage($task, $customer, '14807034902', $address, $assignee);

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
        $users = User::select(['id', 'name'])
            ->where('type', '=', 'serviceman')
            ->where('active', '=', true)
            ->orderBy('name', 'asc')
            ->get();

        $customer = Customer::find($address->customer_id);
        $subcontractors = Subcontractor::select(['id', 'company_name'])->get();

//        $subcontractors = [null];
//        foreach ($subs as $sub) {
//            $subcontractors[] = $sub->company_name;
//        }

        return Inertia::render('Task/Create', [
            'addressId' => $address->id,
            'customerId' => $address->customer_id,
            'assignedServiceman' => $address->assigned_serviceman,
            'subcontractors' => $subcontractors,
            'customer' => $customer,
            'customerName' => $customer->last_name,
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {

//        dd('In Store Method');

        if (!is_null($request->description)) {
            DB::beginTransaction();
            try {
                if ($request->subcontractor) {
                    $this->handleSubcontractorTask($request);
                } elseif ($request->toDo) {
                    $this->handleTodoTask($request);
                } else {
                    $this->handleRepairTask($request);
                }

//                $this->updateJobRates($request);
//                $this->updateServicemanRates($request);

                DB::commit();

                return Redirect::route('customers.show', $request->address_id)
                    ->with('success', 'Task created and message was sent');
            } catch (\Exception $e) {
                DB::rollBack();
                return Redirect::back()->withErrors('An error occurred: ' . $e->getMessage());
            }
        }
    }

    /**
     * Handle subcontractor task creation and notifications.
     */
    private function handleSubcontractorTask(Request $request)
    {
        $task = Task::create([
            'assigned' => $request->subcontractor,
            'customer_id' => $request->customer_id,
            'description' => $request->description,
            'scp_id' => $request->product_number,
            'status' => false,
            'type' => 'referred',
            'price' => 0,
            'sent' => false,
            'address_id' => $request->address_id,
            'been_paid' => false,
            'quantity' => 0,
            'count' => 1,
            'rate' => 0
        ]);

        TaskStatus::create([
            'task_id' => $task->id,
            'status' => 'referred',
            'status_creator' => Auth::id(),
            'status_date' => now()
        ]);

        $customer = Customer::findOrFail($request->customer_id);
        $address = Address::findOrFail($request->address_id);
        $customerUser = User::find($customer->user_id);
        $subcontractor = Subcontractor::findOrFail($request->subcontractor);

        $email = strpos($customerUser->email, '.') !== false ? $customerUser->email : 'Email not recorded';

        $message = "KPS Pools has a job for you.\n=====================\n{$request->description}\n=====================\nCustomer Info::\n{$customer->first_name} {$customer->last_name}\n{$customer->phone_number}\n$email\n{$address->address_line_1}, {$address->city} {$address->zip}\n=====================\nPlease reach out to Shawn for any questions at 14807034902";

        Notification::route('vonage', $subcontractor->phone_number)->notify(new GenericNotification($message));

        Notification::route('vonage', $customer->phone_number)->notify(new GenericNotification("{$subcontractor->company_name} has been notified. They can be reached at:\n=====================\n{$subcontractor->contact_name}\n{$subcontractor->company_name}\n{$subcontractor->phone_number}\n=====================\nPlease reach out to Shawn for any questions at 14807034902"));
    }


    /**
     * Handle internal task marked as 'todo'.
     */
    private function handleTodoTask(Request $request)
    {
        $task = Task::create([
            'assigned' => $request->serviceman,
            'customer_id' => $request->customer_id,
            'description' => $request->description,
            'scp_id' => $request->product_number,
            'status' => 'pickedUp',
            'type' => 'todo',
            'price' => 0,
            'sent' => false,
            'address_id' => $request->address_id,
            'been_paid' => false,
            'quantity' => 0,
            'count' => 1,
            'rate' => 0
        ]);

        TaskStatus::insert([
            ['task_id' => $task->id, 'status' => 'created', 'status_creator' => Auth::id(), 'status_date' => now()],
            ['task_id' => $task->id, 'status' => 'pickedUp', 'status_creator' => Auth::id(), 'status_date' => now()]
        ]);

        $user = User::find($request->serviceman);
        $customer = Customer::find($request->customer_id);

        if (Auth::id() !== $user->id) {
            Notification::route('vonage', $user->phone_number)->notify(new GenericNotification("You were assigned a Task::\n{$customer->first_name} {$customer->last_name}\n{$request->description}\n" . env('APP_URL') . "/customers/show/{$request->address_id}"));
        }
    }

    /**
     * Handle standard repair tasks.
     */
    private function handleRepairTask(Request $request)
    {

        //        if new

//        if existing
        if (
            $request->jobRate &&
            $request->repairRate > -1
        ) {
            $task = Task::create([
                'assigned' => $request->serviceman,
                'customer_id' => $request->customer_id,
                'description' => $request->description,
                'scp_id' => $request->product_number,
                'status' => 'created',
                'type' => 'repair',
                'price' => $request->jobRate,
                'sent' => false,
                'address_id' => $request->address_id,
                'been_paid' => false,
                'count' => 1,
                'quantity' => $request->quantity,
                'rate' => $request->repairRate
            ]);

//            EmployeePayment::addRepairEntry($request->serviceman, $task->id, $request->repairRate);

        } else {
            $task = Task::create([
                'assigned' => $request->serviceman,
                'customer_id' => $request->customer_id,
                'description' => $request->description,
                'scp_id' => $request->product_number,
                'status' => 'created',
                'type' => 'repair',
                'price' => 0,
                'sent' => false,
                'address_id' => $request->address_id,
                'been_paid' => false,
                'count' => 1,
                'quantity' => $request->quantity,
                'rate' => 0
            ]);
        }
        $tasks = Task::where('address_id', $request->address_id)
            ->orderBy('count', 'desc')
            ->get();
        $task->count = $tasks[0]->count + 1;
        $task->save();

        if (!empty($request->publicId)) {
            foreach($request->publicId as $publicId){
                TaskImage::firstOrCreate([
                    'task_id' => $task->id,
                    'public_id' => $publicId
                ]);
            }
        }

        TaskStatus::create([
            'task_id' => $task->id,
            'status' => 'created',
            'status_creator' => Auth::id(),
            'status_date' => now()
        ]);

        $customer = Customer::find($request->customer_id);

        if ($request->jobRate &&
            $request->repairRate > -1) {
            $message = "Please Reply\n==================\n\nKPS Pools needs to inform you about a necessary repair for your pool:\n\n" . $request->description . " Quantity: " . $request->quantity . " for $" . $request->jobRate . "\n\nWould you like for us to complete this for you?\n\nY$task->count for Yes\nN$task->count for No\n\nYou may also reach out to Shawn at 480.703.4902 or 480.622.6441. If you have any questions";
            Notification::route('vonage', $customer->phone_number)->notify(new TaskApprovalNotification($message));
            $task->sent = true;
        } else {
            $message = "New repair type has been created\nLink:: " . env('APP_URL') . "/tasksNeedsApproval"
                . "\nName:: $customer->first_name $customer->last_name\nDescription:: $task->description\n";
            Notification::route('vonage', '14807034902')->notify(new GenericNotification($message));
        }

        $task->save();

    }

    private function updateJobRates(Request $request)
    {
        if ($request->jobRate) {
            $jobType = JobType::updateOrCreate(
                ['name' => $request->description],
                ['rate_charged' => $request->jobRate]
            );

            if ($request->updateStandardRate) {
                $jobType->update(['rate_charged' => $request->jobRate]);
            }

            if ($request->repairRate !== null) {
                $jobType->sub_rate = $request->repairRate;
            }
        }
    }

    /**
     * Update serviceman repair rate in user_job_rates table.
     */
    private function updateServicemanRates(Request $request)
    {
        if ($request->updateServicemanRepairRate && $request->repairRate !== null) {

            $jt = JobType::find($request->selectedTask['jobTypeId']);
            $jt->sub_rate = $request->repairRate;
            $jt->save();
        }
    }

    public function storeBackup(Request $request): RedirectResponse
    {

        dd($request);

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
            'scp_id' => $request->product_number,
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

    public function sendforApproval($task, $phoneNumber, $message = null)
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
