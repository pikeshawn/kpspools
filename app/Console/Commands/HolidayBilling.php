<?php

namespace App\Console\Commands;

use App\Models\Address;
use App\Models\Customer;
use App\Models\ServiceStop;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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
    public function handle(): void
    {
        //
        //        self::generateFlatRateBillingReport();
        self::getFirstServiceDate();
    }

    public function getFirstServiceDate()
    {
        $customers = self::activeCustomersByJemmsonId();

        foreach ($customers as $customer) {
            $cust = Customer::where('jemmson_id', $customer)->get();
            $addresses = Address::where('customer_id', $cust[0]->id)->where('active', true)->where('sold', '<>', 1)->get();
            foreach ($addresses as $address) {
                $firstServiceDate = ServiceStop::where('address_id', $address->id)
                    ->where('service_type', 'Service Stop')
                    ->whereYear('created_at', 2024)
                    ->orderBy('created_at', 'asc')
                    ->value('created_at');
                $results[] = [
                    'Address Id' => $address->id,
                    'First Name' => $cust[0]->first_name,
                    'Last Name' => $cust[0]->last_name,
                    'Address' => $address->address_line_1,
                    //                    'Plan Price' => $planPrice,
                    //                    'Free Weeks' => $freeWeeks,
                    //                    'November Price' => $novemberPrice,
                    //                    'December Price' => $decemberPrice,
                    'First Service Date' => $firstServiceDate,
                    'Jemmson ID' => $cust[0]->jemmson_id,
                ];
            }
        }
        // Generate CSV file
        $csvFilename = 'flat_rate_billing_report.csv';
        $csvFilepath = storage_path("app/{$csvFilename}");

        $handle = fopen($csvFilepath, 'w');

        // Add headers to CSV
        fputcsv($handle, ['Address Id', 'First Name', 'Last Name', 'Address', 'First Service Date', 'Jemmson ID']);

        // Write each row to CSV
        foreach ($results as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

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
                echo 'stop here';
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

    public function activeCustomersByJemmsonId(): array
    {
        return [
            28,
            33,
            40,
            54,
            56,
            58,
            61,
            64,
            65,
            68,
            71,
            75,
            75,
            75,
            75,
            78,
            83,
            85,
            86,
            87,
            88,
            90,
            94,
            101,
            102,
            106,
            111,
            120,
            124,
            128,
            130,
            131,
            133,
            134,
            135,
            136,
            137,
            138,
            141,
            142,
            145,
            147,
            148,
            152,
            154,
            155,
            156,
            157,
            159,
            160,
            161,
            162,
            163,
            164,
            165,
            169,
            170,
            171,
            174,
            175,
            180,
            183,
            185,
            186,
            187,
            189,
            190,
            191,
            193,
            194,
            195,
            198,
            205,
            215,
            216,
            220,
            224,
            225,
            227,
            230,
            231,
            235,
            242,
            243,
            244,
            245,
            246,
            247,
            249,
            250,
            251,
            252,
            253,
            254,
            255,
            256,
            257,
            259,
            262,
            263,
            265,
            265,
            265,
            267,
            267,
            268,
            270,
            271,
            272,
            273,
            274,
            283,
            285,
            291,
            292,
            319,
            319,
            323,
            328,
            330,
            334,
            339,
            346,
            347,
            349,
            350,
            351,
            352,
            357,
            359,
            360,
            361,
            365,
            366,
            367,
            368,
            368,
            370,
            371,
            373,
            374,
            375,
            376,
            378,
            379,
            380,
            381,
            382,
            383,
            384,
            385,
            386,
            387,
            388,
            389,
            390,
            392,
            393,
            394,
            395,
            396,
            398,
            399,
            400,
            401,
            404,
            405,
            406,
            409,
            411,
            412,
            412,
            413,
            415,
            416,
            417,
            418,
            419,
            421,
            423,
            424,
            426,
            427,
            427,
            428,
            429,
            430,
            438,
            439,
            440,
            441,
            442,
            443,
            444,
            445,
            446,
            447,
            448,
            449,
            450,
            451,
            452,
            453,
            454,
            455,
            456,
            457,
            458,
            459,
            462,
            464,
            466,
            466,
            467,
            468,
            470,
            476,
            477,
            478,
            479,
            480,
            482,
            483,
            485,
            486,
            487,
            488,
            489,
            490,
            491,
            492,
            493,
            494,
            495,
            495,
        ];
    }
}
