<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\ServiceStop;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Customer;
use App\Models\Task;
use GuzzleHttp\Client;
use Carbon\Carbon;

class InitiateBidController extends Controller
{
    //

    public function index()
    {

//        get customers to initiate bid
        $customers = Customer::select(['id', 'first_name', 'last_name', 'phone_number'])
            ->where('jemmson_id', null)
            ->where('active', 1)
            ->get();

        foreach ($customers as $customer){
            $address = Address::select(["id"])->where('customer_id', $customer->id)->first();
            $customer->address_id = $address->id;
        }

        return Inertia::render('Initiate/Index', [
            'customers' => $customers
        ]);
    }

    public function customer($id)
    {

        $address = Address::find($id);
        $customer = Customer::find($address->customer_id);
        $serviceStops = ServiceStop::where('customer_id', $customer->id)->get();
        $completedTasks = Task::where('customer_id', $customer->id)
            ->where('status', 'completed')->get();

        return Inertia::render('Initiate/Customer', [
            'customer' => $customer,
            'serviceStops' => $serviceStops,
            'addressId' => $id,
            'completedTasks' => $completedTasks
        ]);
    }

    public function sendBid(Request $request)
    {

//        dd($request);

        $initiatedBid = self::initiateBid($request->customer);

        $initiatedBidJSON = json_decode($initiatedBid);

        // Get the current date and time
        $now = Carbon::now();

        // Format the date as YYYY-MM-DD
        $startDate = $now->format('Y-m-d');

        self::addTasks($request->tasks, $initiatedBidJSON->job->id, $initiatedBidJSON->job->customer_id, $startDate);
        self::submitBid($initiatedBidJSON->job->id, $initiatedBidJSON->job->customer_id);
        self::updateCustomerTable($initiatedBidJSON->job->customer_id, $request->customer['id']);

        return Inertia::render('Initiate/Index');

    }

    public function updateCustomerTable($jemmson_id, $kpsPoolsCustomerId)
    {
        $customer = Customer::find($kpsPoolsCustomerId);
        $customer->jemmson_id = $jemmson_id;
        $customer->save();
    }

    public function submitBid($jobId, $customer_id)
    {
        $client = new Client();

        $response = $client->post(env('API_URL') . '/task/finishedBidNotification', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('BEARER_TOKEN'),
                'Accept' => 'application/json',
            ],
            'json' => [
                "jobId" => $jobId,
                "customerId" => $customer_id,
                "approved" => true,
                "finished" => true
            ]
        ]);

        $response->getBody()->getContents();
    }

    public function addTasks($tasks, $jobId, $customer_id, $startDate)
    {

//        dd($tasks);


        foreach ($tasks as $task) {
            if (isset($task['name'])) {
                self::addTask($task, $jobId, $customer_id, $startDate);
            }
            if (isset($task['description'])) {
                self::addTask($task, $jobId, $customer_id, $startDate);
            }
        }
    }

    public function addTask($task, $jobId, $customer_id, $startDate)
    {

//        dd($task['qty']);

        isset($task['qty']) ? $quantity = $task['qty'] : $quantity = $task['quantity'];
        isset($task['name']) ? $name = $task['name'] : $name = $task['description'];

        $client = new Client();

        $response = $client->post(env('API_URL') . '/task/addTask', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('BEARER_TOKEN'),
                'Accept' => 'application/json',
            ],
            'json' => [
                'area' => '',
                'assetAccountRef' => [
                    'value' => '0',
                    'name' => 'Inventory Asset'
                ],
                'contractorId' => 1,
                'createNew' => true,
                'customer_id' => $customer_id,
                'customer_message' => '',
                'expenseAccountRef' => [
                    'value' => '0',
                    'name' => 'Cost of Goods Sold'
                ],
                'hasQtyUnitError' => false,
                'hasStartDateError' => false,
                'incomeAccountRef' => [
                    'value' => '0',
                    'name' => 'Sales of Product Income'
                ],
                'item_id' => null,
                'invStartDate' => '',
                'jobId' => $jobId,
                'qty' => $quantity,
                'qtyOnHand' => '0',
                'qtyUnit' => '',
                'qtyUnitErrorMessage' => '',
                'start_date' => $startDate,
                'start_when_accepted' => true,
                'startDateErrorMessage' => '',
                'sub_message' => '',
                'subTaskPrice' => '0',
                'taskExists' => '',
                'taskId' => null,
                'taskPrice' => $task['price'],
                'taskName' => $name,
                'trackQtyOnHand' => true,
                'type' => 'Inventory',
                'updateBasePrice' => false,
                'updateTask' => false,
                'useStripe' => false,
                'errors' => [
                    'errors' => []
                ],
                'busy' => true,
                'successful' => false
            ]
        ]);

        $response->getBody()->getContents();

    }

    public function initiateBid($customer)
    {

//        dd($customer);

        isset($customer['phone_number']) ? $phone = $customer['phone_number'] : $phone = $customer['phoneNumber'];
        isset($customer['first_name']) ? $firstName = $customer['first_name'] : $firstName = $customer['firstName'];
        isset($customer['last_name']) ? $lastName = $customer['last_name'] : $lastName = $customer['lastName'];

        $client = new Client();

        $response = $client->post(env('API_URL') . '/initiate-bid', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('BEARER_TOKEN'),
                'Accept' => 'application/json',
            ],
            'json' => [
                'customerName' => $firstName . " " . $lastName,
                'email' => '',
                'firstName' => $firstName,
                'lastName' => $lastName,
                'jobName' => $firstName . "-" . $lastName . "-initial-" . rand(1, 1000),
                'phone' => self::formatPhoneNumber($phone),
                'quickbooks_id' => '',
                'isMobile' => true,
                'id' => '',
                'taxRate' => 0,
                'paymentType' => 'creditCard',
                'paymentTypeDefault' => null,
                'busy' => false,
                'successful' => false,
            ]
        ]);

        return $response->getBody()->getContents();
    }

    private function formatPhoneNumber($n)
    {
        return '(' . $n[1] . $n[2] . $n[3] . ')-' . $n[4] . $n[5] . $n[6] . '-' . $n[7] . $n[8] . $n[9] . $n[10];
    }

}
