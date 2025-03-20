<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Customer;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

//  vendor/bin/phpunit /Users/shawnpike/Documents/code/business/kpspools/tests/Feature/TaskCreationTest.php
//  vendor/bin/phpunit /Users/shawnpike/Documents/code/business/kpspools/tests/Feature/TaskCreationTest.php testTaskIsAddedToDBWithApprovalNotNullStatusDate

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

    public function test_task_is_added_to_db(): void
    {
        self::login();

        $int = random_int(1, 30);

        $taskData = [
            'customer_id' => $int,
            'address_id' => $int,
            'description' => 'chlorine floaty',
            'type' => 'part',
            'status' => 'created',
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

    public function test_task_is_added_to_db_with_approval_not_null_status_date(): void
    {

        self::login();

        $customerId = random_int(1, 30);

        $taskData = [
            'customer_id' => $customerId,
            'description' => 'filter',
            'address_id' => 1,
            'type' => 'repair',
            'assigned' => 2,
            'approval' => true,
            'approvedDate' => '2021-09-09T11:11',
            'status' => 'created',
        ];

        $this->post('/task/store', $taskData);
        $this->assertDatabaseHas('tasks', [
            'customer_id' => $customerId,
            'assigned' => 2,
            'description' => 'filter',
            'type' => 'repair',
            'status' => 'created',
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

        $this->post('/task/changeStatus', $taskStatusDataApproved);

        $this->assertDatabaseHas('task_statuses', $taskStatusDataCreated);
        $this->assertDatabaseHas('task_statuses', $taskStatusDataApproved);

    }

    public function test_task_is_added_to_db_with_approval_null_status_date(): void
    {

        self::login();

        $customerId = random_int(1, 30);

        $taskData = [
            'customer_id' => $customerId,
            'description' => 'filter',
            'address_id' => 1,
            'type' => 'repair',
            'assigned' => 2,
            'approval' => true,
            'approvedDate' => null,
            'status' => 'created',
        ];

        $this->post('/task/store', $taskData);

        $this->assertDatabaseHas('tasks', [
            'customer_id' => $customerId,
            'assigned' => 2,
            'description' => 'filter',
            'type' => 'repair',
            'status' => 'created',
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

        $this->post('/task/changeStatus', $taskStatusDataApproved);

        $this->assertDatabaseHas('task_statuses', $taskStatusDataCreated);
        $this->assertDatabaseHas('task_statuses', $taskStatusDataApproved);

    }

    public function test_task_is_added_to_db_with_not_approved_status_date_not_null(): void
    {

        self::login();

        $customerId = random_int(1, 30);

        $taskData = [
            'customer_id' => $customerId,
            'description' => 'filter',
            'address_id' => 1,
            'type' => 'repair',
            'approval' => false,
            'assigned' => 2,
            'approvedDate' => '2021-09-09T11:11',
            'status' => 'created',
        ];

        $this->post('/task/store', $taskData);
        $this->assertDatabaseHas('tasks', [
            'customer_id' => $customerId,
            'description' => 'filter',
            'type' => 'repair',
            'assigned' => 2,
            'status' => 'created',
        ]);

        $this->assertDatabaseMissing('tasks', [
            'customer_id' => $customerId,
            'description' => 'filter',
            'type' => 'repair',
            'status' => 'approved',
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

    public function test_sending_a_part_should_receive_a_notification(): void
    {
        self::login();

        $int = random_int(1, 30);

        $taskData = [
            'customer_id' => $int,
            'address_id' => 1,
            'description' => 'chlorine floaty',
            'type' => 'part',
            'status' => 'created',
        ];

        $this->post('/task/store', $taskData);
    }

    public function test_sending_a_repair_should_receive_a_notification(): void
    {
        self::login();

        $int = random_int(1, 30);

        $taskData = [
            'customer_id' => $int,
            'address_id' => 1,
            'description' => 'replumb ins and outs',
            'type' => 'repair',
            'status' => 'created',
        ];

        $this->post('/task/store', $taskData);
    }

    public function test_sending_a_todo_should_not_receive_a_notification(): void
    {
        self::login();

        $int = random_int(1, 30);

        $taskData = [
            'customer_id' => $int,
            'address_id' => 1,
            'description' => 'backwash every week',
            'type' => 'todo',
            'status' => 'created',
        ];

        $this->post('/task/store', $taskData);
    }

    private function login()
    {
        $user = User::factory()->create([
            'type' => 'serviceman',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        return $user;
    }

    private function loginAsAdmin()
    {
        $user = User::factory()->isAdmin()->isServiceman()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        return $user;
    }

    public function test_getting_all_tasks_to_from_all_users()
    {
        self::loginAsAdmin();
        //        self::makeAdmin($u);

        //        dd($u);

        $customerId = random_int(1, 10);

        $customer = Customer::find($customerId);
        $address = Address::where('customer_id', $customer->id)->first();

        $taskDataAdmin = [
            'customer_id' => $customerId,
            'address_id' => $address->id,
            'description' => 'filter',
            'type' => 'repair',
            'assigned' => 2,
            'approval' => true,
            'approvedDate' => null,
            'status' => 'created',
        ];

        $taskDataGuy1 = [
            'customer_id' => $customerId,
            'address_id' => $address->id,
            'description' => 'filter',
            'type' => 'repair',
            'assigned' => 3,
            'approval' => true,
            'approvedDate' => null,
            'status' => 'created',
        ];

        $taskDataGuy2 = [
            'customer_id' => $customerId,
            'address_id' => $address->id,
            'description' => 'filter',
            'type' => 'repair',
            'assigned' => 4,
            'approval' => true,
            'approvedDate' => null,
            'status' => 'created',
        ];

        $this->post('/task/store', $taskDataAdmin);
        $this->post('/task/store', $taskDataGuy1);
        $this->post('/task/store', $taskDataGuy2);

        $response = $this->get('tasks');

        $response->assertInertia(fn (Assert $page) => $page->component('Task/Index')->has('tasks'));
        //        $response->assertInertia(fn (Assert $page) => $page->component('Task/ViewPartRepairTasks')->has('Tuesday'));

    }

    public function test_assigning_todo_is_assigned_to_right_serviceman(): void
    {
        self::login();

        $description = $this->faker->name();

        $todo = [
            'address_id' => 4,
            'customer_id' => 4,
            'description' => $description,
            'type' => 'todo',
            'todoAssignee' => 2,
            'approval' => false,
            'approvedDate' => null,
            'status' => 'created',
            'assigned' => 1,
        ];

        $this->post('/task/store', $todo);

        $this->assertDatabaseHas('tasks', [
            'description' => $description,
            'type' => 'todo',
            'assigned' => 2,
            'status' => 'pickedUp']
        );

    }

    public function test_assigned_user_is_the_name_associated_to_the_task_when_returned_to_customer_show_page()
    {
        self::login();

        $description = $this->faker->name();

        $todo = [
            'address_id' => 4,
            'customer_id' => 4,
            'description' => $description,
            'type' => 'todo',
            'todoAssignee' => 2,
            'approval' => false,
            'approvedDate' => null,
            'status' => 'created',
            'assigned' => 1,
        ];

        $description1 = $this->faker->name();

        $todo1 = [
            'address_id' => 4,
            'customer_id' => 4,
            'description' => $description1,
            'type' => 'todo',
            'todoAssignee' => 1,
            'approval' => false,
            'approvedDate' => null,
            'status' => 'created',
            'assigned' => 1,
        ];

        $this->post('/task/store', $todo);
        $this->post('/task/store', $todo1);

        $response = $this->get('/customers/show/4');

        $response->assertInertia(fn (Assert $page) => $page->component('Customers/Show')->has('tasks'));

        //        $response->assertStatus(200)
        //            ->assertJsonFragment(
        //                ['tasks' =>
        //                    [
        //                        'description' => $description,
        //                        'status' => "pickedUp",
        //                        'completed' => false,
        //                        'assigned' => "Jeremiah"
        //                    ],
        //                    [
        //                        'description' => $description1,
        //                        'status' => "pickedUp",
        //                        'completed' => false,
        //                        'assigned' => "Shawn"
        //                    ]
        //                ]
        //            );

    }

    public function test_marking_task_to_be_picked_up(): void
    {
        self::login();

        $task = Task::find(3);
        $task->status = 'approved';
        $task->save();

        $this->assertDatabaseHas('tasks', ['id' => 3, 'status' => 'approved']);

        $taskStatuses = TaskStatus::where('task_id', 3)->get();
        $exists = false;
        $existsId = 0;
        foreach ($taskStatuses as $taskStatus) {
            if ($taskStatus->status == 'approved') {
                $exists = true;
                $existsId = $taskStatus->id;
            }
        }
        if ($exists) {
            $taskStatus = TaskStatus::find($existsId);
            $taskStatus->delete();
        } else {
            $date = Carbon::now();
            $statusDate = $date->format('Y-m-d H:i:s');
            $taskStatus = new TaskStatus;
            $taskStatus->status = 'approved';
            $taskStatus->task_id = $task->id;
            $taskStatus->status_date = $statusDate;
            $taskStatus->save();
        }

        $taskData = [
            [
                'customer_id' => 2,
                'task_id' => 3,
                'address_id' => 1,
                'first_name' => 'Kaitlyn',
                'last_name' => 'Muller',
                'description' => 'filter',
                'type' => 'repair',
                'status' => 'approved',
                'pickedUp' => true,
            ],
        ];

        $this->post('/task/pickedUp', $taskData);

        $this->assertDatabaseHas('tasks', ['id' => 3, 'status' => 'pickedUp']);
    }
}
