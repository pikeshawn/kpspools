<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DetermineFilterCleans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:determine-filter-cleans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // 1. Capture all the filter cleans
        $customerData = DB::table('customers')
            ->join('addresses', 'customers.id', '=', 'addresses.customer_id')
            ->join('filters', 'addresses.id', '=', 'filters.address_id')
            ->where('addresses.active', true)
            ->where('addresses.sold', '!=', 1)
            ->whereIn('filters.type', ['cartridge', 'de'])
            ->select('customers.jemmson_id', 'customers.first_name', 'customers.last_name', 'filters.type')
            ->distinct()
            ->get();

        // Store unique jemmson IDs in an array
        $jemmsonIds = $customerData->pluck('jemmson_id')->unique()->toArray();

        // Initialize CSV file
        $csvFile = storage_path('filters.csv');
        $fileHandle = fopen($csvFile, 'w');
        fputcsv($fileHandle, ['jemmson_id', 'first_name', 'last_name', 'filter_type', 'api_response']);

        // 2. Make API calls for each jemmson_id
        foreach ($jemmsonIds as $jemmsonId) {
            try {
                // Call the submit method (assume it's defined elsewhere in the class)
                $response = $this->submit($jemmsonId);

                // Get customer info for the jemmson ID
                $customer = $customerData->firstWhere('jemmson_id', $jemmsonId);

                $data = json_decode($response, true);

                if (empty($data)) {
                    $response = 'none';
                    $newLine = [
                        $jemmsonId,
                        $customer->first_name,
                        $customer->last_name,
                        $customer->type,
                        'none',
                    ];
                } else {
                    $newLine = [
                        $jemmsonId,
                        $customer->first_name,
                        $customer->last_name,
                        $customer->type,
                    ];
                    foreach ($data as $item) {
                        //                        $csvRows[] = "{$item['filter_clean_name']},{$item['filter_clean_date']}";

                        array_push($newLine, "{$item['filter_clean_name']}");
                        array_push($newLine, "{$item['filter_clean_date']}");

                    } // Or handle the array content
                    //                    $csvString = implode(",", $csvRows);
                    //                    $response = $csvRows;
                    //                    $csvRows = [];
                }

                // Write to CSV
                fputcsv($fileHandle, $newLine);
            } catch (\Exception $e) {
                // Handle exceptions, log errors, and continue
                Log::error("Failed to process jemmson_id {$jemmsonId}: ".$e->getMessage());
            }
        }

        // Close the file
        fclose($fileHandle);

        // Return success
        $this->info('Filter cleans completed and written to filters.csv.');

        return true;
    }

    public function submit($jemmsonId)
    {
        $client = new Client;

        $response = $client->post(env('API_URL').'/filterCleans', [
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
