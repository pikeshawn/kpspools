<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Models\ServiceStop;
use App\Models\Customer;
use App\Models\Address;

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
                    $startDate = Carbon::now()->year . '-01-01';
                    $endDate = Carbon::now()->year . '-03-31';
                    break;
                case 'q2':
                    $startDate = Carbon::now()->year . '-04-01';
                    $endDate = Carbon::now()->year . '-06-30';
                    break;
                case 'q3':
                    $startDate = Carbon::now()->year . '-07-01';
                    $endDate = Carbon::now()->year . '-09-30';
                    break;
                case 'q4':
                    $startDate = Carbon::now()->year . '-10-01';
                    $endDate = Carbon::now()->year . '-12-31';
                    break;
                case 'winter':
                    $startDate = Carbon::now()->year . '-11-01';
                    $endDate = Carbon::now()->addYear()->year . '-03-31';
                    break;
                case 'summer':
                    $startDate = Carbon::now()->year . '-04-01';
                    $endDate = Carbon::now()->year . '-10-31';
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
            $customerName = $customer->first_name . ' ' . $customer->last_name;

            $serviceStops = ServiceStop::where('address_id', $addressId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('service_type', 'Service Stop')
                ->get();

            $totalStops = $serviceStops->count();
            $serviceRate = $address->plan_price / 4;
            $income = $serviceRate * $totalStops;

            $chemicals = round($serviceStops->sum(fn($ss) =>
                ($ss->tabs_whole_mine * 1.65) +
                ($ss->liquid_chlorine * ((19.65 * 1.08) / 4)) +
                ($ss->liquid_acid * ((27 * 1.08) / 4))
            ), 2);

            $labor = $totalStops * 20;

            $gross = round($income - ($chemicals + $labor), 2);
            $grossPercentage = round(1-($chemicals+$labor)/$income, 2);

            $results[] = [
                'name' => $customerName,
                'address' => $address->address_line_1 . " " . $address->city . " " . $address->zip,
                'planPrice' => $address->plan_price,
                'totalStops' => $totalStops,
                'serviceRate' => $serviceRate,
                'revenue' => $income,
                'chemicals' => $chemicals,
                'labor' => $labor,
                'gross' => $gross,
                'grossPercentage' => $grossPercentage
            ];
        }

        return response()->json($results);
    }

}
