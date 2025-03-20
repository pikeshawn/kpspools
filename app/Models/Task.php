<?php

namespace App\Models;

use App\Notifications\TaskApprovalNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $tsStatusArray;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }

    public function task_statuses(): HasMany
    {
        return $this->hasMany(TaskStatus::class);
    }

    public function subcontractor(): HasMany
    {
        return $this->hasMany(Subcontractor::class);
    }

    public static function sendApprovalMessage($task, $customer, $phoneNumber, $address)
    {
        $message = "An approved repair has been assigned to you:: $task->description.\n$customer->first_name $customer->last_name\n".env('APP_URL')."/customers/show/$address->id Please text or call Shawn if you have any questions";
        Notification::route('vonage', $phoneNumber)->notify(new TaskApprovalNotification($message));
    }

    public static function sendCompletedMessage($task, $customer, $phoneNumber, $address, $assigned)
    {

        if ($task->type === 'repair' || $task->type === 'part') {
            $message = "$task->description has been completed by $assigned. Please text or call Shawn if you have any questions";
            Notification::route('vonage', $customer->phone_number)->notify(new TaskApprovalNotification($message));
        }

        $message = "A repair has been completed by $assigned, $task->description.\n$customer->first_name $customer->last_name\n".env('APP_URL')."/customers/show/$address->id. Please take a look";
        Notification::route('vonage', $phoneNumber)->notify(new TaskApprovalNotification($message));
    }

    public static function allIncompleteTasks()
    {

        //        I want all tasks that are assigned to the authenticated user
        //        this means that the tasks come from the task table where the status is approved

        $tasks = [];

        $approvedTasks = Task::where('assigned', Auth::user()->getAuthIdentifier())
            ->where(function ($query) {
                $query->where('status', 'approved')
                    ->orWhere('status', 'pickedUp');
            })
            ->get();

        foreach ($approvedTasks as $approved) {

            $customer = Customer::find($approved->customer_id);

            $cust = [];
            $cust['customer_id'] = $customer->id;
            $cust['quantity'] = $approved->quantity;
            $cust['task_id'] = $approved->id;
            $cust['first_name'] = $customer->first_name;
            $cust['last_name'] = $customer->last_name;
            $cust['description'] = $approved->description;
            $cust['scp_id'] = $approved->scp_id;
            $cust['type'] = $approved->type;
            $cust['assigned'] = $approved->assigned;
            $cust['status'] = $approved->status;
            $cust['pickedUp'] = false;
            array_push($tasks, $cust);

        }

        $allTasks = collect($tasks);

        return $allTasks;
    }

    public static function allIncompleteTasksByNonAdminPoolGuy()
    {
        return Task::where('status', '<>', 'completed')
            ->where('assigned', '=', Auth::user()->id)->get();
    }

    public static function allCompletedTasksRelatedToSpecificCustomer($addressId): Collection
    {
        $allEnabledTasks = [];
        $t = Task::where('address_id', $addressId)->where('status', 'completed')->get();

        foreach ($t as $task) {
            $line = [];
            $line['id'] = $task->id;
            $line['description'] = $task->description;
            $line['status'] = $task->status;
            $line['completed'] = false;
            $allEnabledTasks[] = $line;
        }

        return collect($allEnabledTasks);
    }

    public static function allCreatedTasks(): Collection
    {
        $tasks = [];
        $customers = Customer::has('tasks')->get();

        foreach ($customers as $customer) {
            $custTasks = Task::where('customer_id', $customer->id)->where('sent', '<>', 1)->get();

            foreach ($custTasks as $task) {

                $address = Address::find($task->address_id);

                if ($task->status === 'created' ||
                    $task->status === 'approved' ||
                    $task->status === 'denied' ||
                    $task->status === 'diy'
                ) {
                    $cust = [];
                    $cust['customer_id'] = $customer->id;
                    $cust['address_id'] = $task->address_id;
                    $cust['address'] = $address->address_line_1.' '.$address->city.' '.$address->zip;
                    $cust['phone_number'] = $customer->phone_number;
                    $cust['product_number'] = $task->scp_id;
                    $cust['task_id'] = $task->id;
                    $cust['sub_rate'] = $task->rate;
                    $cust['first_name'] = $customer->first_name;
                    $cust['last_name'] = $customer->last_name;
                    $cust['description'] = $task->description;
                    $cust['type'] = $task->type;
                    $cust['status'] = $task->status;
                    $cust['assigned'] = $task->assigned;
                    $cust['price'] = $task->price;
                    $cust['deleted'] = false;
                    $cust['pickedUp'] = false;
                    $cust['sent'] = $task->sent;
                    if ($task->status == 'approved') {
                        $cust['approved'] = true;
                    } else {
                        $cust['approved'] = false;
                    }

                    $serviceman = User::find($task->assigned);
                    $cust['name'] = $serviceman->name;

                    array_push($tasks, $cust);
                }
            }
        }

        return collect($tasks);
    }

    public static function allCustomerCreatedTasks($customerId, $addressId): Collection
    {
        $tasks = [];

        //        $customerWithTasks = Customer::whereHas('tasks', function ($query) use ($customerId, $addressId) {
        //            $query->where('customer_id', $customerId)->where('address_id', $addressId);
        //        })->with('tasks')->get();

        $allTasks = Task::where('address_id', $addressId)->get();
        $customer = Customer::find($customerId);

        //        dd($allTasks);

        //        foreach ($allTasks as $specificTask) {
        //            $custTasks = Task::where('customer_id', $customer->id)->get();

        foreach ($allTasks as $task) {
            if ($task->status === 'created' ||
                $task->status === 'approved' ||
                $task->status === 'denied' ||
                $task->status === 'diy'
            ) {
                $cust = [];
                $cust['customer_id'] = $task->customer_id;
                $cust['address_id'] = $addressId;
                $cust['phone_number'] = $customer->phone_number;
                $cust['task_id'] = $task->id;
                $cust['first_name'] = $customer->first_name;
                $cust['last_name'] = $customer->last_name;
                $cust['description'] = $task->description;
                $cust['type'] = $task->type;
                $cust['status'] = $task->status;
                $cust['scp_id'] = $task->scp_id;
                $cust['assigned'] = $task->assigned;
                $cust['price'] = $task->price;
                $cust['deleted'] = false;
                $cust['pickedUp'] = false;
                $cust['sent'] = $task->sent;
                if ($task->status == 'approved') {
                    $cust['approved'] = true;
                } else {
                    $cust['approved'] = false;
                }

                $serviceman = User::find($task->assigned);
                $cust['name'] = $serviceman->name;

                array_push($tasks, $cust);
            }
        }
        //        }

        return collect($tasks);
    }

    public static function allPickedUpTasksRelatedToSpecificCustomer($addressId): Collection
    {
        $allEnabledTasks = [];
        $t = Task::where('address_id', $addressId)->get();

        if ($t->isNotEmpty()) {
            foreach ($t as $task) {
                $user = User::find($task->assigned);
                $line = [];
                $line['id'] = $task->id;
                $line['description'] = $task->description;
                $line['status'] = $task->status;
                $line['price'] = $task->price;
                $line['completed'] = false;
                $line['assigned'] = $user->name;
                $allEnabledTasks[] = $line;
            }
        } else {
            foreach ($t as $task) {
                $line = [];
                $line['id'] = $task->id;
                $line['description'] = $task->description;
                $line['status'] = $task->status;
                $line['completed'] = false;
                $line['assigned'] = '';
                $allEnabledTasks[] = $line;
            }
        }

        return collect($allEnabledTasks);
    }

    public static function allTasksForPoolGuy()
    {
        $allEnabledTasks = [];
        if (Auth::user()->is_admin == 1) {
            $t = Task::where('status', 'pickedUp')
                ->orWhere('status', 'completed')
                ->orWhere('status', 'created')
                ->orWhere('status', 'approved')
                ->get();
        } else {
            $t = Task::where('assigned', Auth::user()->id)->get();
        }

        //        $t = Task::where('status', 'pickedUp')
        //            ->get();

        //        dd($t);

        foreach ($t as $task) {
            $c = Customer::find($task->customer_id);
            $a = Address::find($task->address_id);
            $poolGuy = User::find($task->assigned);

            if ($c === null) {
                Log::debug("CUSTOMER IS NULL\n\ntask :: $task\ncustomer :: $c\naddress :: $a\npoolguy :: $poolGuy\n");
            }

            if ($a === null) {
                Log::debug("ADDRESS IS NULL\n\ntask :: $task\ncustomer :: $c\naddress :: $a\npoolguy :: $poolGuy\n");
            }

            if ($poolGuy === null) {
                Log::debug("POOLGUY IS NULL\n\ntask :: $task\ncustomer :: $c\naddress :: $a\npoolguy :: $poolGuy\n");
            }

            $line = [];
            $line['id'] = $task->id;
            $line['customerName'] = "$c->first_name $c->last_name";
            $line['poolGuy'] = "$poolGuy->name";
            $line['customerPhoneNumber'] = $c->phone_number;
            $line['address'] = "$a->address_line_1, $c->city $c->state $c->zip";
            $line['description'] = $task->description;
            $line['status'] = $task->status;
            $line['deleted'] = false;
            $line['completed'] = false;
            $allEnabledTasks[] = $line;
        }

        return collect($allEnabledTasks);
    }
}
