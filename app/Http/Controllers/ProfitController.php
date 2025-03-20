<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\ScpInvoice;
use App\Models\ScpInvoiceItem;
use Inertia\Inertia;

class ProfitController extends Controller
{
    //

    public function index()
    {
        $totalSCPCost = ScpInvoice::sum('sales_order_amount');
        $totalActiveCustomers = Address::where('sold', false)->where('customer_id', '<>', 101)->where('active', '=', 1)->count('active');
        $totalServiceAmount = Address::where('active', 1)->where('sold', false)->sum('plan_price');
        $totalAverageServiceAmount = Address::where('active', 1)->where('sold', false)->average('plan_price');

        $totalAverageChemCostPerMonth = self::getTotalAverageChemCostPerMonth();

        return Inertia::render('Profit/Index', [
            'totalSpent' => $totalSCPCost,
            'totalActiveCustomers' => $totalActiveCustomers,
            'totalServiceAmount' => $totalServiceAmount,
            'totalAverageChemCostPerMonth' => $totalAverageChemCostPerMonth,
            'totalAverageServiceAmount' => $totalAverageServiceAmount,
        ]);
    }

    private function getTotalAverageChemCostPerMonth()
    {

        $totalChems2023 = ScpInvoiceItem::where('description', '=', 'GAL REFILLABLE SANI-CHLOR')
            ->orWhere('description', '=', 'GAL REFILLABLE MURIATIC ACID')
            ->orWhere('description', 'like', '%TABS%')
            ->where('created_at', '>', '01-01-2023 00:00:00')
            ->where('created_at', '<', '01-01-2024 00:00:00')
            ->sum('ext_cost');

        $totalChems2024 = ScpInvoiceItem::where('description', '=', 'GAL REFILLABLE SANI-CHLOR')
            ->orWhere('description', '=', 'GAL REFILLABLE MURIATIC ACID')
            ->orWhere('description', 'like', '%TABS%')
            ->where('created_at', '>', '01-01-2024 00:00:00')
            ->sum('ext_cost');

        return [
            'twentyThree' => $totalChems2023 / 12,
            'twentyFour' => $totalChems2024 / 3,
        ];
    }
}
