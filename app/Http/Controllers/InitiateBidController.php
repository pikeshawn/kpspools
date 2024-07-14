<?php

namespace App\Http\Controllers;

use App\Models\ServiceStop;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Customer;
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

        return Inertia::render('Initiate/Index', [
            'customers' => $customers
        ]);
    }

    public function customer($id)
    {

        $customer = Customer::find($id);
        $serviceStops = ServiceStop::where('customer_id', $customer->id)->get();

        return Inertia::render('Initiate/Customer', [
            'customer' => $customer,
            'serviceStops' => $serviceStops,
        ]);
    }

    public function sendBid(Request $request)
    {

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

    private function updateCustomerTable($jemmson_id, $kpsPoolsCustomerId)
    {
        $customer = Customer::find($kpsPoolsCustomerId);
        $customer->jemmson_id = $jemmson_id;
        $customer->save();
    }

    private function submitBid($jobId, $customer_id)
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

    private function addTasks($tasks, $jobId, $customer_id, $startDate)
    {

//        dd($tasks[3]['name']);

        foreach ($tasks as $task) {
            if (isset($task['name'])) {
                self::addTask($task, $jobId, $customer_id, $startDate);
            }
        }
    }

    private function addTask($task, $jobId, $customer_id, $startDate)
    {

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
                'qty' => 1,
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
                'taskName' => $task['name'],
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

    private function initiateBid($customer)
    {

        $client = new Client();

        $response = $client->post(env('API_URL') . '/initiate-bid', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('BEARER_TOKEN'),
                'Accept' => 'application/json',
            ],
            'json' => [
                'customerName' => $customer['first_name'] . " " . $customer['last_name'],
                'email' => '',
                'firstName' => $customer['first_name'],
                'lastName' => $customer['last_name'],
                'jobName' => $customer['first_name'] . "-" . $customer['last_name'] . "-initial-" . rand(1, 1000),
                'phone' => self::formatPhoneNumber($customer['phone_number']),
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
