<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Customer;
use App\Models\EmployeePayment;
use App\Models\ServiceStop;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportsController extends Controller
{
    //

    public function index()
    {
        return Inertia::render('Reports/Index', [

        ]);
    }

    public function accrual()
    {
        return Inertia::render('Reports/Accrual', [

        ]);
    }

    public function accrualAll(Request $request)
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        if ($request->period) {
            switch ($request->period) {
                case 'last_month':
                    $startDate = Carbon::now()->subMonth()->startOfMonth();
                    $endDate = Carbon::now()->subMonth()->endOfMonth();
                    break;
                case 'last_year':
                    $startDate = Carbon::now()->subYear()->startOfYear();
                    $endDate = Carbon::now()->subYear()->endOfYear();
                    break;
                case 'q1':
                    $startDate = Carbon::now()->year.'-01-01';
                    $endDate = Carbon::now()->year.'-03-31';
                    break;
                case 'q2':
                    $startDate = Carbon::now()->year.'-04-01';
                    $endDate = Carbon::now()->year.'-06-30';
                    break;
                case 'q3':
                    $startDate = Carbon::now()->year.'-07-01';
                    $endDate = Carbon::now()->year.'-09-30';
                    break;
                case 'q4':
                    $startDate = Carbon::now()->year.'-10-01';
                    $endDate = Carbon::now()->year.'-12-31';
                    break;
                case 'winter':
                    $startDate = Carbon::now()->year.'-11-01';
                    $endDate = Carbon::now()->addYear()->year.'-03-31';
                    break;
                case 'summer':
                    $startDate = Carbon::now()->year.'-04-01';
                    $endDate = Carbon::now()->year.'-10-31';
                    break;
            }
        }

        $addressIds = ServiceStop::whereBetween('created_at', [$startDate, $endDate])
            ->where('service_type', 'Service Stop')
            ->pluck('address_id')
            ->unique();

        $results = [];

        foreach ($addressIds as $addressId) {
            $address = Address::find($addressId);
            $customer = Customer::find($address->customer_id);
            $customerName = $customer->first_name.' '.$customer->last_name;

            $serviceStops = ServiceStop::where('address_id', $addressId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('service_type', 'Service Stop')
                ->get();

            $totalStops = $serviceStops->count();
            $serviceRate = $address->plan_price / 4;
            $income = $serviceRate * $totalStops;

            $labor = self::getLaborCost($serviceStops);

            $chemicals = round($serviceStops->sum(fn ($ss) => ($ss->tabs_whole_mine * 1.65) +
                ($ss->liquid_chlorine * ((19.65 * 1.08) / 4)) +
                ($ss->liquid_acid * ((27 * 1.08) / 4))
            ), 2);

            $gross = round($income - ($chemicals + $labor), 2);
            $grossPercentage = round((1 - ($chemicals + $labor) / $income) * 100, 2);

            $results[] = [
                'name' => $customerName,
                'addressId' => $address->id,
                'address' => $address->address_line_1.' '.$address->city.' '.$address->zip,
                'planPrice' => $address->plan_price,
                'totalStops' => $totalStops,
                'active' => $address->active ? 'yes' : 'no',
                'sold' => $address->sold ? 'yes' : 'no',
                'serviceRate' => $serviceRate,
                'revenue' => $income,
                'chemicals' => $chemicals,
                'labor' => $labor,
                'gross' => $gross,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'grossPercentage' => $grossPercentage,
            ];
        }

        $totalStopsInTimePeriod = 0;
        $totalChemicalsInTimePeriod = 0;
        $leastProfitableCustomerInTimePeriod = 0;
        $mostProfitableCustomerInTimePeriod = 0;

        return response()->json($results);
    }

    private function getLaborCost($serviceStops)
    {
        $labor = 0;
        foreach ($serviceStops as $serviceStop) {
            /*
             *          if $serviceStop created date was before 3/10/2025
             *              then $labor = $labor + 20
             *          else
             *             $ep = EmployeePayments::where('service_stop_id', $serviceStop->id)->first()
             *             $labor = $labor + $ep->rate;
             * */
            $serviceStopDate = Carbon::parse($serviceStop->created_at);
            $cutoffDate = Carbon::parse('2025-03-10');

            if ($serviceStopDate->lessThan($cutoffDate)) {
                $labor += 20;
            } else {
                $ep = EmployeePayment::where('service_stop_id', $serviceStop->id)->first();
                if ($ep) {
                    $labor += $ep->rate;
                }
            }
        }

        return $labor;

    }

    public function customerRows($addressId, $startDate, $endDate)
    {
        /*
         * - need to pull all service stops in the service_stops table
         * that fall between the startDate and endDate and have the given
         * addressId
         * - startDate and endDate with reference created_at in the service_stops table
         * - addressId with reference the address_id column
         * - return a inertia response to page Reports/Customer
         */

        // Convert start and end dates to Carbon instances for proper comparison

        if ($startDate == 'begin') {
            $ss = ServiceStop::where('address_id', $addressId)->first();

            if (is_null($ss)) {
                return Inertia::render('Reports/Customer', [
                    'serviceStops' => $ss,
                    'addressId' => $addressId,
                ]);
            }

            $startDate = $ss->created_at;
            $endDate = Carbon::now();

        } else {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
        }

        // Query the service_stops table
        $serviceStops = ServiceStop::where('address_id', $addressId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($serviceStops as $serviceStop) {
            $customer = Customer::find($serviceStop->customer_id);
            $user = User::find($serviceStop->user_id);
            $address = Address::find($serviceStop->address_id);

            $serviceStop->customer_name = "$customer->first_name $customer->last_name";
            $serviceStop->serviceman_name = "$user->name";
            $serviceStop->address = "$address->address_line_1";

        }

        // Return the data as an Inertia response
        return Inertia::render('Reports/Customer', [
            'serviceStops' => $serviceStops,
            'addressId' => $addressId,
        ]);
    }
}
