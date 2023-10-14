<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

//  vendor/bin/phpunit /Users/shawnpike/Documents/code/business/kpspools/tests/Feature/TaskCreationTest.php

class TaskCreationTest extends TestCase
{
//    use WithFaker;
    /**
     * A basic feature test example.
     */

//    public function __construct()
//    {
//        $this->setUpFaker();
//    }

    public function testTaskIsAddedToDB(): void
    {
        self::login();

        $int = random_int(1, 10000);

        $taskData = [
            'customer_id' => $int,
            'description' => 'chlorine floaty',
            'type' => 'part',
            'status' =>'created'
        ];

        $this->post('/task/store', $taskData);
        $this->assertDatabaseHas('tasks', $taskData);

        $task = Task::where('customer_id', '=', $taskData['customer_id'])->first();

        $taskStatusData = [
            'task_id' => $task->id,
            'status' =>'created',
        ];

        $this->assertDatabaseHas('task_statuses', $taskStatusData);
    }

    private function login()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
    }
}
