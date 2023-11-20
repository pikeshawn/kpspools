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
            $query->where('status', '<>', 'completed')->where('type', '<>', 'todo');
        }])->get();
        foreach ($customers as $customer){
            $custTasks = Task::where('customer_id', $customer->id)->get();

            foreach ($custTasks as $task) {
                $cust = [];
                $cust['first_name'] = $customer->first_name;
                $cust['last_name'] = $customer->last_name;
                $cust['description'] = $task->description;
                $cust['status'] = $task->status;
                array_push($tasks, $cust);
            }
        }

        $allTasks = collect($tasks);

//        dd($allTasks);

        return $allTasks;
    }

    public function allIncompleteTasksByNonAdminPoolGuy()
    {
        return Task::where('status', '<>', 'completed')
            ->where('assigned', '=', Auth::user()->id);
    }
}
