<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    static public function allCompletedTasksRelatedToSpecificCustomer($customerId)
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

    static public function allPickedUpTasksRelatedToSpecificCustomer($customerId)
    {
        $allEnabledTasks = [];
        $t = Task::where('customer_id', $customerId)->where('status', 'pickedUp')->get();

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
