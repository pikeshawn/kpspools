<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

//  vendor/bin/phpunit /Users/shawnpike/Documents/code/business/kpspools/tests/Feature/TaskCreationTest.php

class TaskCreationTest extends TestCase
{
    use WithFaker;
//    use RefreshDatabase;
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

        $int = random_int(1, 30);

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

        $customerId = random_int(1, 30);

        $taskData = [
            'customer_id' => $customerId,
            "description" => "filter",
            "type" => "repair",
            "assigned" => 2,
            "approval" => true,
            "approvedDate" => "2021-09-09T11:11",
            "status" => "created"
        ];

        $this->post('/task/store', $taskData);
        $this->assertDatabaseHas('tasks', [
            "customer_id" => $customerId,
            "assigned" => 2,
            "description" => "filter",
            "type" => "repair",
            "status" => "approved"
        ]);

        $task = Task::where('customer_id', '=', $taskData['customer_id'])->first();

        $taskStatusDataCreated = [
            'task_id' => $task->id,
            'status' => 'created',
        ];

        $taskStatusDataApproved = [
            'task_id' => $task->id,
            'status' => 'approved',
            'status_date' => '2021-09-09 11:11:00',
        ];

        $this->assertDatabaseHas('task_statuses', $taskStatusDataCreated);
        $this->assertDatabaseHas('task_statuses', $taskStatusDataApproved);

    }

    public function testTaskIsAddedToDBWithApprovalNullStatusDate(): void
    {

        self::login();

        $customerId = random_int(1, 30);

        $taskData = [
            'customer_id' => $customerId,
            "description" => "filter",
            "type" => "repair",
            "assigned" => 2,
            "approval" => true,
            "approvedDate" => null,
            "status" => "created"
        ];

        $this->post('/task/store', $taskData);
        $this->assertDatabaseHas('tasks', [
            "customer_id" => $customerId,
            "assigned" => 2,
            "description" => "filter",
            "type" => "repair",
            "status" => "approved"
        ]);

        $task = Task::where('customer_id', '=', $taskData['customer_id'])->first();

        $taskStatusDataCreated = [
            'task_id' => $task->id,
            'status' => 'created',
        ];

        $taskStatusDataApproved = [
            'task_id' => $task->id,
            'status' => 'approved',
        ];

        $this->assertDatabaseHas('task_statuses', $taskStatusDataCreated);
        $this->assertDatabaseHas('task_statuses', $taskStatusDataApproved);

    }

    public function testTaskIsAddedToDBWithNotApprovedStatusDateNotNull(): void
    {

        self::login();

        $customerId = random_int(1, 30);

        $taskData = [
            'customer_id' => $customerId,
            "description" => "filter",
            "type" => "repair",
            "approval" => false,
            "assigned" => 2,
            "approvedDate" => "2021-09-09T11:11",
            "status" => "created"
        ];

        $this->post('/task/store', $taskData);
        $this->assertDatabaseHas('tasks', [
            "customer_id" => $customerId,
            "description" => "filter",
            "type" => "repair",
            "assigned" => 2,
            "status" => "created"
        ]);

        $this->assertDatabaseMissing('tasks', [
            "customer_id" => $customerId,
            "description" => "filter",
            "type" => "repair",
            "status" => "approved"
        ]);

        $task = Task::where('customer_id', '=', $taskData['customer_id'])->first();

        $taskStatusDataCreated = [
            'task_id' => $task->id,
            'status' => 'created',
        ];

        $taskStatusDataApproved = [
            'task_id' => $task->id,
            'status' => 'approved',
        ];

        $this->assertDatabaseHas('task_statuses', $taskStatusDataCreated);
        $this->assertDatabaseMissing('task_statuses', $taskStatusDataApproved);

    }

    public function testSendingAPartShouldReceiveANotification(): void
    {
        self::login();

        $int = random_int(1, 30);

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

        $int = random_int(1, 30);

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

        $int = random_int(1, 30);

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

        return $user;
    }

    private function loginAsAdmin()
    {
        $user = User::factory()->isAdmin()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        return $user;
    }

    public function testGettingAllTasksToFromAllUsers()
    {
        self::loginAsAdmin();
//        self::makeAdmin($u);

//        dd($u);

        $customerId = random_int(1, 30);

        $taskDataAdmin = [
            'customer_id' => $customerId,
            "description" => "filter",
            "type" => "repair",
            "assigned" => 2,
            "approval" => true,
            "approvedDate" => null,
            "status" => "created"
        ];

        $taskDataGuy1 = [
            'customer_id' => $customerId,
            "description" => "filter",
            "type" => "repair",
            "assigned" => 3,
            "approval" => true,
            "approvedDate" => null,
            "status" => "created"
        ];

        $taskDataGuy2 = [
            'customer_id' => $customerId,
            "description" => "filter",
            "type" => "repair",
            "assigned" => 4,
            "approval" => true,
            "approvedDate" => null,
            "status" => "created"
        ];

        $this->post('/task/store', $taskDataAdmin);
        $this->post('/task/store', $taskDataGuy1);
        $this->post('/task/store', $taskDataGuy2);

        $response = $this->get('tasks');

        $response->assertInertia(fn (Assert $page) => $page->component('Task/Index')->has('tasks')->has('assigned'));
//        $response->assertInertia(fn (Assert $page) => $page->component('Task/ViewPartRepairTasks')->has('Tuesday'));

    }


}
