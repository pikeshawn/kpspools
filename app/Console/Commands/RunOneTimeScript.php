<?php

namespace App\Console\Commands;

use App\Models\Address;
use App\Models\Task;
use App\Models\User;
use Illuminate\Console\Command;
use function Sodium\add;

class RunOneTimeScript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-one-time-script';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //

        $users = User::where('id', '<', 15)
            ->where('active', true)
            ->get();

//        echo $users;


        foreach ($users as $user) {
            $addresses = Address::where('serviceman_id', $user->id)
                ->where('active', true)->get();
            $dontMatch = 0;
            $match = 0;
            foreach ($addresses as $address) {
                $tasks = Task::where('address_id', $address->id)
                    ->whereNot('status', 'completed')
                    ->whereNot('status', 'invoiced')
                    ->whereNot('status', 'paid')->get();
                foreach ($tasks as $task) {
                    if ($task->assigned != $user->id) {
                        $dontMatch++;
                    } else {
                        $match++;
                    }
                }
            }
            echo "User:: $user->name\nDont Match :: $dontMatch\nMatch:: $match\n";
        }


        foreach ($users as $user) {
            $addresses = Address::where('serviceman_id', $user->id)
                ->where('active', true)->get();
            foreach ($addresses as $address) {
                $tasks = Task::where('address_id', $address->id)
                    ->whereNot('status', 'completed')
                    ->whereNot('status', 'invoiced')
                    ->whereNot('status', 'paid')->get();
                foreach ($tasks as $task) {
                    $task->assigned = $user->id;
                    $task->save();
                }
            }
        }

        foreach ($users as $user) {
            $addresses = Address::where('serviceman_id', $user->id)
                ->where('active', true)->get();
            $dontMatch = 0;
            $match = 0;
            foreach ($addresses as $address) {
                $tasks = Task::where('address_id', $address->id)
                    ->whereNot('status', 'completed')
                    ->whereNot('status', 'invoiced')
                    ->whereNot('status', 'paid')->get();
                foreach ($tasks as $task) {
                    if ($task->assigned != $user->id) {
                        $dontMatch++;
                    } else {
                        $match++;
                    }
                }
            }
            echo "User:: $user->name\nDont Match :: $dontMatch\nMatch:: $match\n";
        }

    }
}
