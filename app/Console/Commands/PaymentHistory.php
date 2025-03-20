<?php

namespace App\Console\Commands;

use App\Models\Customer;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:payment-history';

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
        $addressIds = [
            1,
            14,
            17,
            24,
            25,
            27,
            40,
            65,
            51,
            72,
            79,
            91,
            104,
            102,
            116,
            107,
            110,
            113,
            115,
            109,
            117,
            120,
            126,
            127,
            128,
            125,
            131,
            132,
            136,
            137,
            143,
            144,
            149,
            157,
            152,
            151,
            169,
            170,
            173,
            174,
            175,
            285,
            177,
            178,
            179,
            180,
            181,
            182,
            183,
            188,
            189,
            194,
            190,
            192,
            205,
            207,
            211,
            212,
            213,
            215,
            217,
            222,
            227,
            230,
            241,
            242,
            246,
            253,
            256,
            262,
            263,
            276,
            277,
            278,
            304,
            199,
            284,
            292,
            296,
            305,
            310,
            330,
            329,
            337,
            338,
            348,
            355,
            358,
            359,
            362,
            373,
            377,
            381,
            382,
            383,
            394,
            395,
            396,
            397,
            398,
            405,
            407,
            409,
            411,
            417,
            418,
            419,
            421,
            424,
            427,
            429,
            430,
            432,
            437,
            441,
            442,
            444,
            449,
            455,
            364,
        ];

        self::generatePaymentHistoryCsv($addressIds);
    }

    public static function generatePaymentHistoryCsv(array $addressIds)
    {
        $fileName = 'PaymentHistory.csv';
        //        $filePath = storage_path('app/' . $fileName);
        $filePath = storage_path($fileName);

        // Open a file for writing
        $file = fopen($filePath, 'w');

        // Write the header row
        fputcsv($file, ['Name', 'Billed Date', 'Billed Amount', 'Payment Date', 'Payment Amount']);

        // Cycle through address IDs
        foreach ($addressIds as $addressId) {
            // Pull the customer_id from the addresses table
            $customer = DB::table('addresses')
                ->where('addresses.id', $addressId)
                ->join('customers', 'addresses.customer_id', '=', 'customers.id')
                ->select('customers.id as customer_id', 'customers.jemmson_id', 'customers.first_name', 'customers.last_name')
                ->first();

            if (! $customer) {
                continue; // Skip if no customer found
            }

            $fullName = $customer->first_name.' '.$customer->last_name;

            fputcsv($file, [
                $fullName,
            ]);

            // Submit an API request to jemmson
            $response = self::submitToJemmson($customer->jemmson_id);

            if ($response) {
                $jsonResponse = json_decode($response);
                foreach ($jsonResponse as $row) {
                    // Add rows for each entry in the JSON array
                    fputcsv($file, [
                        '',
                        $row->billedDate ?? '',
                        $row->billedAmount ?? '',
                        $row->datePaid ?? '',
                        $row->paidAmount ?? '',
                    ]);
                }
            }
        }

        // Close the file
        fclose($file);

        // Save the file in storage
        Storage::put($fileName, file_get_contents($filePath));

        return $filePath;
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
