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

    static public function allCompletedTasksRelatedToSpecificCustomer($customerId): Collection
    {
        $allEnabledTasks = [];
        $t = Task::where('customer_id', $customerId)->where('status', 'completed')->get();

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
        $customers = Customer::with(['tasks' => function ($query) {
            $query->where('status', 'created');
        }])->get();

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
                   if ($task->status == 'approved'){
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

    static public function allPickedUpTasksRelatedToSpecificCustomer($customerId): Collection
    {
        $allEnabledTasks = [];
        $t = Task::where('customer_id', $customerId)->get();

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
}
