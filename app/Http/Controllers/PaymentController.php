<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Task;
use App\Models\EmployeePayment;
use App\Models\ServiceStop;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    //
    /**
     * Show the form for creating a new resource.
     */
    public function index(Address $address): Response
    {

        $customer = Customer::find($address->customer_id);

        $response = self::submitToJemmson($customer->jemmson_id);
        $jsonResponse = json_decode($response);

        return Inertia::render('Payments/Index', [
            'customer_name' => $customer->first_name . ' ' . $customer->last_name,
            'history' => $jsonResponse
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function currentPaycheck(): Response
    {

        $servicemanId = Auth::user()->id;

        $totalPendingServiceStops = EmployeePayment::where('serviceman_id', $servicemanId)
            ->where('status', 'pending')
            ->whereNotNull('service_stop_id')
            ->count();

        // Total Service Stop Amount
        $totalServiceStopAmount = EmployeePayment::where('serviceman_id', $servicemanId)
            ->where('status', 'pending')
            ->whereNotNull('service_stop_id')
            ->sum('rate');

        // Total Repair Amount
        $totalPendingRepairs = EmployeePayment::where('serviceman_id', $servicemanId)
            ->where('status', 'pending')
            ->whereNotNull('task_id')
            ->count();

        // Total Repair Amount
        $totalRepairAmount = EmployeePayment::where('serviceman_id', $servicemanId)
            ->where('status', 'pending')
            ->whereNotNull('task_id')
            ->sum('rate');

        // Total Bucket Amount
        $totalBucketAmount = EmployeePayment::where('serviceman_id', $servicemanId)
            ->where('bucket_rate', '>', 0)
            ->sum('bucket_rate');

        // Total Bucket Amount
        $totalNumberOfPoolsContributingToBucket = EmployeePayment::where('serviceman_id', $servicemanId)
            ->where('bucket_rate', '>', 0)
            ->count();


        return Inertia::render('Payments/Paychecks', [
            'totalPendingServiceStops' => $totalPendingServiceStops,
            'totalServiceStopAmount' => $totalServiceStopAmount,
            'totalPendingRepairs' => $totalPendingRepairs,
            'totalRepairAmount' => $totalRepairAmount,
            'totalNumberOfPoolsContributingToBucket' => $totalNumberOfPoolsContributingToBucket,
            'totalBucketAmount' => $totalBucketAmount
        ]);
    }

    public function serviceStops()
    {

        $servicemanId = Auth::user()->id;

        // Fetch employee payments with associated service stops and customers
        $employeePayments = EmployeePayment::where('serviceman_id', $servicemanId)
            ->whereNotNull('service_stop_id') // Ensure it's linked to a service stop
            ->where('status', 'pending') // Filter by pending status
            ->get()
            ->map(function ($payment) {
                // Retrieve related service stop
                $serviceStop = ServiceStop::find($payment->service_stop_id);

                // Retrieve customer details
                $customer = Customer::find($serviceStop->customer_id ?? null);

                return [
                    'id' => $payment->service_stop_id,
                    'customer_name' => $customer ? $customer->first_name . ' ' . $customer->last_name : 'Unknown',
                    'date_service' => $serviceStop ? $serviceStop->created_at->format('Y-m-d') : null,
                    'rate' => number_format($payment->rate, 2),
                    'status' => $payment->status,
                    'link' => "/ss/" . $payment->service_stop_id,
                ];
            });

        return response()->json($employeePayments);
    }

    public function repairs()
    {
        $servicemanId = Auth::user()->id;

        // Fetch employee payments with associated tasks and customers
        $employeePayments = EmployeePayment::where('serviceman_id', $servicemanId)
            ->whereNotNull('task_id') // Ensure it's linked to a task
            ->where('status', 'pending') // Filter by pending status
            ->get()
            ->map(function ($payment) {
                // Retrieve related task
                $task = Task::find($payment->task_id);

                // Retrieve customer details
                $customer = Customer::find($task->customer_id ?? null);

                return [
                    'id' => $payment->task_id,
                    'customer_name' => $customer ? $customer->first_name . ' ' . $customer->last_name : 'Unknown',
                    'date_service' => $task ? $task->created_at->format('Y-m-d') : null,
                    'paid_amount' => number_format($payment->rate, 2),
                    'status' => $payment->status,
                ];
            });

        return response()->json($employeePayments);
    }

    private static function submitToJemmson($jemmsonId)
    {
        $client = new Client();

        $response = $client->post(env('API_URL') . '/payment-history', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('BEARER_TOKEN'),
                'Accept' => 'application/json',
            ],
            'json' => [
                "jemmsonId" => $jemmsonId
            ]
        ]);

        return $response->getBody()->getContents();
    }

}
