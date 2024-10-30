<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\Address;
use App\Models\ServiceStop;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class HolidayBilling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:holiday-billing';

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
        self::generateFlatRateBillingReport();
    }

    /**
     * Generate a billing report for flat-rate monthly customers.
     *
     * @return bool
     */
    public function generateFlatRateBillingReport()
    {
        $results = [];

        // Fetch all active addresses with a monthly plan duration and sold is not 1
        $addresses = Address::where('active', 1)
            ->where('plan_duration', 'monthly')
            ->where('sold', '!=', 1)
            ->with('customer')  // Assuming relationship with Customer model
            ->get();

        $i = 0;

        foreach ($addresses as $address) {

            $i++;

            $customer = $address->customer;
            $planPrice = $address->plan_price;
            $freeWeeks = 0;

            $addressId = $address->id;

            // Iterate over each month in 2024
            for ($month = 1; $month <= 12; $month++) {
                // Count the number of distinct service weeks in the current month
                $serviceCount = ServiceStop::where('address_id', $addressId)
                    ->whereYear('created_at', 2024)
                    ->whereMonth('created_at', $month)
                    ->where('service_type', 'Service Stop')
                    ->count();

                // If service count is greater than 4, add 1 to freeWeeks
                if ($serviceCount > 4) {
                    $freeWeeks++;
                }

//                // Record the last service week date for reporting
//                $lastServiceStop = ServiceStop::where('address_id', $addressId)
//                    ->whereYear('created_at', 2024)
//                    ->whereMonth('created_at', $month)
//                    ->where('service_type', 'Service Stop')
//                    ->orderBy('created_at', 'desc')
//                    ->first();

//                if ($lastServiceStop) {
//                    $lastServiceWeekDate = $lastServiceStop->created_at;
//                }
            }

            // Calculate November and December prices based on freeWeeks
            if ($freeWeeks === 0) {
                $novemberPrice = $planPrice * 0.05;
                $decemberPrice = $planPrice * 0.5;
            } elseif ($freeWeeks === 1) {
                $novemberPrice = $planPrice * 0.75;
                $decemberPrice = $planPrice * 0.5;
            } elseif ($freeWeeks === 2) {
                $novemberPrice = $planPrice * 0.75;
                $decemberPrice = $planPrice * 0.75;
            } elseif ($freeWeeks === 3) {
                $novemberPrice = $planPrice * 0.75;
                $decemberPrice = $planPrice * 1;
            } else { // 4 or more free weeks
                $novemberPrice = $planPrice * 1;
                $decemberPrice = $planPrice * 1;
            }

            if ($i === 244) {
                echo "stop here";
            }

            $firstServiceDate = ServiceStop::where('address_id', $address->id)
                ->where('service_type', 'Service Stop')
                ->whereYear('created_at', 2024)
                ->orderBy('created_at', 'asc')
                ->value('created_at');

            // Append the results for CSV generation
            $results[] = [
                'Address Id' => $addressId,
                'First Name' => $customer->first_name,
                'Last Name' => $customer->last_name,
                'Address' => $address->address_line_1,
                'Plan Price' => $planPrice,
                'Free Weeks' => $freeWeeks,
                'November Price' => $novemberPrice,
                'December Price' => $decemberPrice,
                'First Service Date' => $firstServiceDate,
                'Jemmson ID' => $customer->jemmson_id,
            ];
        }

        // Generate CSV file
        $csvFilename = 'flat_rate_billing_report.csv';
        $csvFilepath = storage_path("app/{$csvFilename}");

        $handle = fopen($csvFilepath, 'w');

        // Add headers to CSV
        fputcsv($handle, ['Address Id', 'First Name', 'Last Name', 'Address', 'Plan Price', 'Free Weeks', 'November Price', 'December Price', 'First Service Date', 'Jemmson ID']);

        // Write each row to CSV
        foreach ($results as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        // Optionally save CSV in Laravel storage (e.g., local, S3)
//        Storage::disk('local')->put($csvFilename, file_get_contents($csvFilepath));

        return true;
    }

}
