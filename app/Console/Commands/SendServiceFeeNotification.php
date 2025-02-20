<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\GenericNotification;
use App\Models\Customer;
use App\Models\OneTimePassCode;

class SendServiceFeeNotification extends Command
{
    protected $signature = 'send:service-fee-notification';
    protected $description = 'Send service fee update notification to active customers';

    public function handle()
    {
//        dd("handle");

        $customers = Customer::join('addresses', 'customers.id', '=', 'addresses.customer_id')
            ->where('addresses.active', 1)
            ->where('addresses.sold', '<>', 1)
            ->select('customers.id', 'customers.first_name', 'customers.last_name', 'customers.phone_number')
            ->get();

        foreach ($customers as $customer) {
            $code = Str::random(8);

            // Store code in one_time_pass_codes table
            OneTimePassCode::create([
                'user_id' => $customer->id,
                'one_time_code' => $code,
                'consumed' => false,
                'expires_at' => null,
                'job_id' => null,
            ]);

            $link = env('APP_URL') . "/terms_of_service/service_fee/" . $code;
            $message = "Hi {$customer->first_name} {$customer->last_name}, we've updated our Service Call policy to ensure efficient and expert troubleshooting. " .
                "Going forward, we charge an $80 Service Call Fee for onsite diagnostics and consultation. " .
                "This fee is applied toward any approved repairs. Please review and accept our updated terms here: $link " .
                "Let us know if you have any questions!";

            // Send SMS notification
            Notification::route('vonage', $customer->phone_number)
                ->notify(new GenericNotification($message));
        }

        $this->info('Service fee notifications sent successfully.');
    }
}
