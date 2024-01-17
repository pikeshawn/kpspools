<?php

namespace Tests\Feature\Profit;

use App\Models\ServiceStop;
use App\Models\Customer;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfitTest extends TestCase
{

//  vendor/bin/phpunit /home/forge/kpspools.com/tests/Feature/Profit/ProfitTest.php

    public function test_I_can_pull_the_profit_for_each_customer_and_create_a_csv_file(): void
    {
        // get all Users that are currently active and that have had service stops in the last year
        $customers = ServiceStop::where('time_in', '>', '2023-01-01 00:00:00')
            ->where('time_in', '<', '2024-01-01 00:00:00')
            ->distinct()
            ->pluck('customer_id');

        foreach ($customers as $customerId) {
            $customer = Customer::find($customerId);
            if ($customer->active) {
                $serviceStops = ServiceStop::where('customer_id', $customerId)
                    ->where('service_type', 'Service Stop')
                    ->where('time_in', '>', '2023-01-01 00:00:00')
                    ->where('time_in', '<', '2024-01-01 00:00:00')
                    ->distinct()->get();
                $liquidChlorine = 0;
                foreach ($serviceStops as $ss){
                    $liquidChlorine = $liquidChlorine + $ss->liquid_chlorine;
                }

//                dd($customer->id);
                dd($liquidChlorine);
            }
        }

        echo $customer[0];
    }

}
