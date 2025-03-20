<?php

namespace App\Console\Commands;

use App\Models\Customer;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PaymentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:payment-reminder';

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
        //
        // Fetch all active customers with the necessary fields
        $customers = Customer::where('active', true)
            ->whereIn('last_name', ['Leon', 'Erfat', 'Dallio'])
            ->get(['jemmson_id', 'payment_type', 'autopay', 'date_to_run_card', 'terms', 'last_name']);

        // Loop through each customer and submit the payment request
        foreach ($customers as $customer) {
            try {
                // Extract customer details
                $jemmsonId = $customer->jemmson_id;
                $paymentType = $customer->payment_type;
                $autopay = $customer->autopay;
                $dateToRunCard = $customer->date_to_run_card;
                $terms = $customer->terms;

                // Submit the request to the Jemmson API
                $response = self::submit($jemmsonId, $paymentType, $autopay, $dateToRunCard, $terms);

                if ($response) {
                    Log::info("Successfully submitted payment reminder for customer ID: {$customer->id}");
                } else {
                    Log::warning("Failed to submit payment reminder for customer ID: {$customer->id}");
                }
            } catch (\Exception $e) {
                Log::error("Error processing customer ID: {$customer->id}. Error: ".$e->getMessage());
            }
        }

        Log::info('Daily task has run successfully.');

        return true; // Task completed
    }

    public function submit($jemmsonId, $paymentType, $autopay, $dateToRunCard, $terms)
    {
        $client = new Client;

        $response = $client->post(env('API_URL').'/paymentReminder', [
            'headers' => [
                'Authorization' => 'Bearer '.env('BEARER_TOKEN'),
                'Accept' => 'application/json',
            ],
            'json' => [
                'jemmsonId' => $jemmsonId,
                'paymentType' => $paymentType,
                'autopay' => $autopay,
                'dateToRunCard' => $dateToRunCard,
                'terms' => $terms,
            ],
        ]);

        return $response->getBody()->getContents();
    }
}
