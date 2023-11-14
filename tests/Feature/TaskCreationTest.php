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
            'status' => 'created'
        ];

        $this->post('/task/store', $taskData);
        $this->assertDatabaseHas('tasks', $taskData);

        $task = Task::where('customer_id', '=', $taskData['customer_id'])->first();

        $taskStatusData = [
            'task_id' => $task->id,
            'status' => 'created',
        ];

        $this->assertDatabaseHas('task_statuses', $taskStatusData);
    }

    public function testTaskIsAddedToDBWithApprovalNotNullStatusDate(): void
    {

        self::login();

        $taskData = [
            "customer_id" => 101,
            "description" => "filter",
            "type" => "repair",
            "approval" => true,
            "approvedDate" => "2021-09-09T11:11",
            "status" => "created"
        ];

        $this->post('/task/store', $taskData);
        $this->assertDatabaseHas('tasks', $taskData);

        $task = Task::where('customer_id', '=', $taskData['customer_id'])->first();

        $taskStatusData = [
            'task_id' => $task->id,
            'status' => 'created',
        ];

        $this->assertDatabaseHas('task_statuses', $taskStatusData);

        $taskStatusData = [
            'task_id' => $task->id,
            'status' => 'approved',
            'status_date' => '2021-09-09 11:11:00',
        ];

        $this->assertDatabaseHas('task_statuses', $taskStatusData);
    }

    public function testSendingAPartShouldReceiveANotification(): void
    {
        self::login();

        $int = random_int(1, 10000);

        $taskData = [
            'customer_id' => $int,
            'description' => 'chlorine floaty',
            'type' => 'part',
            'status' => 'created'
        ];

        $this->post('/task/store', $taskData);
    }

    public function testSendingARepairShouldReceiveANotification(): void
    {
        self::login();

        $int = random_int(1, 10000);

        $taskData = [
            'customer_id' => $int,
            'description' => 'replumb ins and outs',
            'type' => 'repair',
            'status' => 'created'
        ];

        $this->post('/task/store', $taskData);
    }

    public function testSendingATodoShouldNotReceiveANotification(): void
    {
        self::login();

        $int = random_int(1, 10000);

        $taskData = [
            'customer_id' => $int,
            'description' => 'backwash every week',
            'type' => 'todo',
            'status' => 'created'
        ];

        $this->post('/task/store', $taskData);
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
