<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function task_statuses()
    {
        return $this->hasMany(TaskStatus::class);
    }

    static public function allIncompleteTasks()
    {
        $tasks = [];
        $customers = Customer::with(['tasks' => function ($query) {
            $query->where('status', '<>', 'completed')
                ->where('type', '<>', 'todo');
        }])->get();
        foreach ($customers as $customer) {
            $custTasks = Task::where('customer_id', $customer->id)->get();

            foreach ($custTasks as $task) {

                if ($task->assigned === Auth::user()->id) {
                    $cust = [];
                    $cust['customer_id'] = $customer->id;
                    $cust['task_id'] = $task->id;
                    $cust['first_name'] = $customer->first_name;
                    $cust['last_name'] = $customer->last_name;
                    $cust['description'] = $task->description;
                    $cust['type'] = $task->type;
                    $cust['assigned'] = $task->assigned;
                    $cust['status'] = $task->status;
                    $cust['pickedUp'] = false;
                    array_push($tasks, $cust);
                }

            }
        }

        $allTasks = collect($tasks);

//        dd($allTasks);

        return $allTasks;
    }

    static public function allIncompleteTasksByNonAdminPoolGuy()
    {
        return Task::where('status', '<>', 'completed')
            ->where('assigned', '=', Auth::user()->id)->get();
    }

    static public function allCompletedTasksRelatedToSpecificCustomer($addressId): Collection
    {
        $allEnabledTasks = [];
        $t = Task::where('address_id', $addressId)->where('status', 'completed')->get();

        foreach ($t as $task) {
            $line = [];
            $line["id"] = $task->id;
            $line["description"] = $task->description;
            $line["status"] = $task->status;
            $line["completed"] = false;
            $allEnabledTasks[] = $line;
        }

        return collect($allEnabledTasks);
    }

    static public function allCreatedTasks(): Collection
    {
        $tasks = [];
        $customers = Customer::has('tasks')->get();

        foreach ($customers as $customer) {
            $custTasks = Task::where('customer_id', $customer->id)->get();


            foreach ($custTasks as $task) {
                if ($task->status === 'created' ||
                    $task->status === 'approved' ||
                    $task->status === 'denied' ||
                    $task->status === 'diy'
                ) {

                    $cust = [];
                    $cust['customer_id'] = $customer->id;
                    $cust['phone_number'] = $customer->phone_number;
                    $cust['task_id'] = $task->id;
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

    static public function allCustomerCreatedTasks($customerId): Collection
    {
        $tasks = [];

        $customerWithTasks = Customer::whereHas('tasks', function ($query) use ($customerId) {
            $query->where('customer_id', $customerId);
        })->with('tasks')->get();

        foreach ($customerWithTasks as $customer) {
            $custTasks = Task::where('customer_id', $customer->id)->get();


            foreach ($custTasks as $task) {
                if ($task->status === 'created' ||
                    $task->status === 'approved' ||
                    $task->status === 'denied' ||
                    $task->status === 'diy'
                ) {

                    $cust = [];
                    $cust['customer_id'] = $customer->id;
                    $cust['phone_number'] = $customer->phone_number;
                    $cust['task_id'] = $task->id;
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

    static public function allPickedUpTasksRelatedToSpecificCustomer($addressId): Collection
    {
        $allEnabledTasks = [];
        $t = Task::where('address_id', $addressId)->get();

        if ($t->isNotEmpty()) {
            foreach ($t as $task) {
                $user = User::find($task->assigned);
                $line = [];
                $line["id"] = $task->id;
                $line["description"] = $task->description;
                $line["status"] = $task->status;
                $line["completed"] = false;
                $line["assigned"] = $user->name;
                $allEnabledTasks[] = $line;
            }
        } else {
            foreach ($t as $task) {
                $line = [];
                $line["id"] = $task->id;
                $line["description"] = $task->description;
                $line["status"] = $task->status;
                $line["completed"] = false;
                $line["assigned"] = '';
                $allEnabledTasks[] = $line;
            }
        }

        return collect($allEnabledTasks);
    }

    static public function allTasksForPoolGuy()
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
            $line["id"] = $task->id;
            $line["customerName"] = "$c->first_name $c->last_name";
            $line["poolGuy"] = "$poolGuy->name";
            $line["customerPhoneNumber"] = $c->phone_number;
            $line["address"] = "$a->address_line_1, $c->city $c->state $c->zip";
            $line["description"] = $task->description;
            $line["status"] = $task->status;
            $line['deleted'] = false;
            $line["completed"] = false;
            $allEnabledTasks[] = $line;
        }

        return collect($allEnabledTasks);
    }
}
