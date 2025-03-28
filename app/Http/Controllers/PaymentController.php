<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Customer;
use App\Models\EmployeePayment;
use App\Models\ServiceStop;
use App\Models\Task;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

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
            'customer_name' => $customer->first_name.' '.$customer->last_name,
            'history' => $jsonResponse,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function currentPaycheck(): Response
    {

        if (Auth::user()->is_admin) {
            $servicemen = User::select(['id', 'name'])->where('type', 'serviceman')->where('active', 1)->get();
        } else {
            $servicemen = User::select(['id', 'name'])->where('type', 'serviceman')->where('id', Auth::user()->id)->where('active', 1)->get();
        }

        $totalPayments = [];

        foreach ($servicemen as $serviceman) {
            array_push($totalPayments, self::currentPayments($serviceman->id, $serviceman->name));
        }

        return Inertia::render('Payments/Paychecks', [
            'totalPayments' => $totalPayments,
        ]);
    }

    public function currentPayments($servicemanId, $servicemanName)
    {

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

        return [
            'totalPendingServiceStops' => $totalPendingServiceStops,
            'totalServiceStopAmount' => $totalServiceStopAmount,
            'totalPendingRepairs' => $totalPendingRepairs,
            'totalRepairAmount' => $totalRepairAmount,
            'totalNumberOfPoolsContributingToBucket' => $totalNumberOfPoolsContributingToBucket,
            'totalBucketAmount' => $totalBucketAmount,
            'servicemanId' => $servicemanId,
            'servicemanName' => $servicemanName,
        ];
    }

    public function serviceStops($column, $direction, $user)
    {

        // Fetch employee payments with associated service stops and customers
        $employeePayments = EmployeePayment::where('serviceman_id', $user)
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
                    'customer_name' => $customer ? $customer->first_name.' '.$customer->last_name : 'Unknown',
                    'date_service' => $serviceStop ? $serviceStop->created_at->format('Y-m-d') : null,
                    'rate' => number_format($payment->rate, 2),
                    'status' => $payment->status,
                    'link' => '/ss/'.$payment->service_stop_id,
                ];
            });

        if ($column === 'name') {
            if ($direction === 'asc') {
                $employeePayments = $employeePayments->sortBy('customer_name');
            } else {
                $employeePayments = $employeePayments->sortByDesc('customer_name');
            }
        } else {
            if ($direction === 'asc') {
                $employeePayments = $employeePayments->sortBy('id');
            } else {
                $employeePayments = $employeePayments->sortByDesc('id');
            }
        }

        return response()->json($employeePayments->values()->all());
    }

    public function repairs($column, $direction, $user)
    {

        // Fetch employee payments with associated tasks and customers
        $employeePayments = EmployeePayment::where('serviceman_id', $user)
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
                    'customer_name' => $customer ? $customer->first_name.' '.$customer->last_name : 'Unknown',
                    'date_service' => $task ? $task->created_at->format('Y-m-d') : null,
                    'paid_amount' => number_format($payment->rate, 2),
                    'status' => $payment->status,
                ];
            });

        if ($column === 'name') {
            if ($direction === 'asc') {
                $employeePayments = $employeePayments->sortBy('customer_name');
            } else {
                $employeePayments = $employeePayments->sortByDesc('customer_name');
            }
        } else {
            if ($direction === 'asc') {
                $employeePayments = $employeePayments->sortBy('id');
            } else {
                $employeePayments = $employeePayments->sortByDesc('id');
            }
        }

        return response()->json($employeePayments->values()->all());
    }

    private static function submitToJemmson($jemmsonId)
    {
        $client = new Client;

        $response = $client->post(env('API_URL').'/payment-history', [
            'headers' => [
                'Authorization' => 'Bearer '.env('BEARER_TOKEN'),
                'Accept' => 'application/json',
            ],
            'json' => [
                'jemmsonId' => $jemmsonId,
            ],
        ]);

        return $response->getBody()->getContents();
    }
}
