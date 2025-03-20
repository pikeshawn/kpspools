<?php

namespace App\Console\Commands;

use App\Http\Controllers\TaskController;
use App\Models\Customer;
use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TaskApprovalReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:task-approval-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        //        pull all tasks from the task table that are created and have been sent
        //        status = "created" and sent = 1
        $tasks = Task::where('status', 'created')->where('sent', true)->get();

        //        cycle through each of the tasks and get the customer that is associated with the task
        foreach ($tasks as $task) {

            $message = "Please Reply\n==================\n\nKPS Pools needs to inform you about a necessary repair for your pool:\n\n".$task->description.' for $'.$task->price."\n\nWould you like for us to complete this for you?\n\nY$task->count for Yes\nN$task->count for No\n\nYou may also reach out to Shawn at 480.703.4902 or 480.622.6441. If you have any questions";

            $customer = Customer::find($task->customer_id);
            $tc = new TaskController;
            $tc->sendforApproval($task, $customer->phone_number, $message);
        }

        //        send a text reminding the customer to approve or deny the task - there will be a message and also
        //        a way to respond to it

        Log::info('Daily task has run successfully.');

        return true; // Task completed
    }
}
