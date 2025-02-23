<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EmployeePayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function logServiceStopPayment($serviceStop)
    {
        // Get the authenticated user
        $user = Auth::user();

        if ($user->bucket) {
            $bucketRate = $user->bucket_rate;
        } else {
            $bucketRate = 0;
        }

        // Insert record into employee_payments table
        EmployeePayment::create([
            'serviceman_id' => $user->id,
            'service_stop_id' => $serviceStop->id,
            'paycheck_id' => null,
            'task_id' => null,
            'rate' => $user->service_rate, // Fetching rate from Users table
            'bucket_rate' => $bucketRate, // Fetching rate from Users table
            'status' => 'pending'
        ]);

    }

    public static function addRepairEntry($servicemanId, $taskId, $rate)
    {

        $entry = EmployeePayment::where('task_id', $taskId)->get();

        if ($entry->isEmpty()) {
            $ep = new EmployeePayment([
                'serviceman_id' => $servicemanId,
                'task_id' => $taskId,
                'rate' => $rate,
                'status' => 'pending',
            ]);
            $ep->save();
        }

    }
}
