<?php

namespace App\Console\Commands;

use App\Models\Address;
use App\Models\ServiceStop;
use App\Models\Customer;
use App\Models\ScpInvoice;
use App\Models\ScpInvoiceItem;
use App\Models\Task;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

class RunOneTimeScript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-one-time-script';

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
//        self::syncCustomersAndAddressTable();
//        self::associateTasksWithServiceman();


//        self::importingInvoices();
//        self::setCorrectCountForTasks();
        self::pullCostDataPerCustomerStoreInACSVile();
    }

    public function pullCostDataPerCustomerStoreInACSVile()
    {

//        Models
//          Customer
//          ServiceStop
//          Address

//        Database
//          customers table
//                id, First Name, Last Name
//          addresses table
//                customer_id, Plan Price, active, sold, chemicals_included
//          service_stops table
//                customer_id, address_id, tabs_whole_mine, liquid_chlorine, liquid_acid

//        Database Relations
//          Customer has many addresses
//          Address has many service stops
//          Customer has many service stops

//        Use Carbon for calculating the dates, eloquent for database access

//        tabs_whole_mine * 1.65 = tabsCost
//        liquid_chlorine * (19.65 * 1.08) / 4 = bottleLiquidChlorine
//        tabs_whole_mine liquid_acid * (27 * 1.08) / 4 = bottleAcid


//        Go through each customer that has 48 or more service stops in the past year from todays date
//        where the active column in the address table is true, chemicals_include is true, and the sold column is false
//        calculate the costs of the chemicals for liquid chlorine, acid, and tabs
//          for a year as a total
//          for May, June, and July of this year, have these three months totaled as a cost
//        multiplier would be the cost of the 3 months of chems over the cost of the year of chems
//        the CSV line would be customer name, plan price, 12 month total, 3 month cost, 12 month cost

//      count the number types of services there are per customer.
//      It comes from the service_stops table and the service_type column;
//      add the costs for chemicals for each of those service types


        $today = Carbon::today();
        $oneYearAgo = $today->copy()->subYear();

        $customers = Customer::whereHas('addresses', function ($query) {
            $query->where('active', true)->where('chemicals_included', true)->where('sold', false);
        })
            ->whereHas('serviceStops', function ($query) use ($oneYearAgo) {
                $query->where('created_at', '>=', $oneYearAgo);
            }, '>=', 48)
            ->get();

        $addresses = Address::where('active', true)->where('chemicals_included', true)->where('sold', false)
            ->whereHas('serviceStops', function ($query) use ($oneYearAgo) {
                $query->where('created_at', '>=', $oneYearAgo);
            }, '>=', 48)
            ->get();

//        number of months that we have had the account

        $csvData = [];
        foreach ($addresses as $address) {
            $totalYearlyCost = 0;
            $totalThreeMonthsCost = 0;

            // Fetch service stops for the customer within the last year
            $serviceStops = ServiceStop::where('address_id', $address->id)
                ->whereBetween('created_at', [$oneYearAgo, $today])
                ->get();

            // Group service stops by service type
            $serviceTypes = $serviceStops->groupBy('service_type');

            // Initialize service type count and cost
            $serviceTypeCount = [];
            $serviceTypeCosts = [];

            foreach ($serviceTypes as $type => $stops) {
                $serviceTypeCount[$type] = $stops->count();
                $serviceTypeCosts[$type] = 0; // Initialize cost for this service type

                foreach ($stops as $stop) {
                    $tabsCost = $stop->tabs_whole_mine * 1.65;
                    $bottleLiquidChlorine = $stop->liquid_chlorine * (19.65 * 1.08) / 4;
                    $bottleAcid = $stop->liquid_acid * (27 * 1.08) / 4;

                    $totalCost = $tabsCost + $bottleLiquidChlorine + $bottleAcid;
                    $totalYearlyCost += $totalCost;
                    $serviceTypeCosts[$type] += $totalCost;

                    $createdAt = Carbon::parse($stop->created_at);
                    if ($createdAt->month >= 5 && $createdAt->month <= 7 && $createdAt->year == $today->year) {
                        $totalThreeMonthsCost += $totalCost;
                    }
                }
            }

            if ($totalYearlyCost > 0) {
                $multiplier = $totalThreeMonthsCost / $totalYearlyCost;

                $planPrice = $address->plan_price;

//                dd($serviceTypeCount['Service Stop']);

                isset($serviceTypeCount['Service Stop']) ? $serviceStopCount = $serviceTypeCount['Service Stop'] : $serviceStopCount = 0;
                isset($serviceTypeCount['Repair']) ? $repairCount = $serviceTypeCount['Repair'] : $repairCount = 0;
                isset($serviceTypeCount['Clear Green Pool']) ? $clearGreenPoolCount = $serviceTypeCount['Clear Green Pool'] : $clearGreenPoolCount = 0;
                isset($serviceTypeCount['Chemical']) ? $chemicalCount = $serviceTypeCount['Chemical'] : $chemicalCount = 0;
                isset($serviceTypeCount['Missed Service']) ? $missedServiceCount = $serviceTypeCount['Missed Service'] : $missedServiceCount = 0;
                isset($serviceTypeCosts['Service Stop']) ? $serviceStopCost = $serviceTypeCosts['Service Stop'] : $serviceStopCost = 0;
                isset($serviceTypeCosts['Repair']) ? $repairCost = $serviceTypeCosts['Repair'] : $repairCost = 0;
                isset($serviceTypeCosts['Clear Green Pool']) ? $clearGreenPoolCost = $serviceTypeCosts['Clear Green Pool'] : $clearGreenPoolCost = 0;
                isset($serviceTypeCosts['Chemical']) ? $chemicalCost = $serviceTypeCosts['Chemical'] : $chemicalCost = 0;
                isset($serviceTypeCosts['Missed Service']) ? $missedServiceCost = $serviceTypeCosts['Missed Service'] : $missedServiceCost = 0;

                $customer = Customer::find($address->customer_id);

                $csvData[] = [
                    'Customer Name' => $customer->first_name . ' ' . $customer->last_name,
                    'Address' => $address->address_line_1 . ', ' . $address->city . ' ' . $address->zip,
                    'Plan Price' => $planPrice,
                    '12 Month Total' => $totalYearlyCost,
                    '3 Month Cost' => $totalThreeMonthsCost,
                    'Multiplier' => $multiplier,
                    'Service Stop Count' => $serviceStopCount,
                    'Repair Count' => $repairCount,
                    'Clear Green Pool Count' => $clearGreenPoolCount,
                    'Chemical Stop Count' => $chemicalCount,
                    'Missed Service Count' => $missedServiceCount,
                    'Service Stop Costs' => $serviceStopCost,
                    'Repair Costs' => $repairCost,
                    'Clear Green Pool Costs' => $clearGreenPoolCost,
                    'Chemical Stop Costs' => $chemicalCost,
                    'Missed Service Costs' => $missedServiceCost,
                ];
            }
        }

        $this->generateCSV($csvData);
        $this->info('Customer costs calculated and CSV generated successfully.');

    }

    private function generateCSV(array $data)
    {
        $filename = storage_path("app/customer_costs.csv");
        $handle = fopen($filename, 'w');
        fputcsv($handle, array_keys($data[0]));

        foreach ($data as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);
    }

    private function syncCustomersAndAddressTable()
    {
        $addresses = Address::with('customer')->get();

        foreach ($addresses as $address) {
            $customer = $address->customer;

            if ($customer) {
                $needsUpdate = false;

                if ($customer->assigned_serviceman !== $address->assigned_serviceman) {
                    $customer->assigned_serviceman = $address->assigned_serviceman;
                    $needsUpdate = true;
                }

                if ($customer->service_day !== $address->service_day) {
                    $customer->service_day = $address->service_day;
                    $needsUpdate = true;
                }

                if ($customer->serviceman_id !== $address->serviceman_id) {
                    $customer->serviceman_id = $address->serviceman_id;
                    $needsUpdate = true;
                }

                if ($needsUpdate) {
                    $customer->save();
                }
            }
        }
    }

    private function setCorrectCountForTasks()
    {
        $addresses = Task::select(['address_id'])->distinct()->get();

//        dd($addresses[0]->address_id);

        foreach ($addresses as $address) {
//            dd($address->address_id);
            $tasks = Task::where('address_id', $address->address_id)->get();
            $count = 0;
            foreach ($tasks as $task) {
//                dd($task->count);
                $count++;
                $task->count = $count;
                $task->save();
            }
        }

    }

    private function associateTasksWithServiceman()
    {
        //

        $users = User::where('id', '<', 15)
            ->where('active', true)
            ->get();

//        echo $users;


        foreach ($users as $user) {
            $addresses = Address::where('serviceman_id', $user->id)
                ->where('active', true)->where('sold', false)->get();
            $dontMatch = 0;
            $match = 0;
            foreach ($addresses as $address) {
                $tasks = Task::where('address_id', $address->id)
                    ->whereNot('status', 'completed')
                    ->whereNot('status', 'invoiced')
                    ->whereNot('status', 'paid')->get();
                foreach ($tasks as $task) {
                    if ($task->assigned != $user->id) {
                        $dontMatch++;
                    } else {
                        $match++;
                    }
                }
            }
            echo "User:: $user->name\nDont Match :: $dontMatch\nMatch:: $match\n";
        }


        foreach ($users as $user) {
            $addresses = Address::where('serviceman_id', $user->id)
                ->where('active', true)->where('sold', false)->get();
            foreach ($addresses as $address) {
                $tasks = Task::where('address_id', $address->id)
                    ->whereNot('status', 'completed')
                    ->whereNot('status', 'invoiced')
                    ->whereNot('status', 'paid')->get();
                foreach ($tasks as $task) {
                    $task->assigned = $user->id;
                    $task->save();
                }
            }
        }

        foreach ($users as $user) {
            $addresses = Address::where('serviceman_id', $user->id)
                ->where('active', true)->where('sold', false)->get();
            $dontMatch = 0;
            $match = 0;
            foreach ($addresses as $address) {
                $tasks = Task::where('address_id', $address->id)
                    ->whereNot('status', 'completed')
                    ->whereNot('status', 'invoiced')
                    ->whereNot('status', 'paid')->get();
                foreach ($tasks as $task) {
                    if ($task->assigned != $user->id) {
                        $dontMatch++;
                    } else {
                        $match++;
                    }
                }
            }
            echo "User:: $user->name\nDont Match :: $dontMatch\nMatch:: $match\n";
        }
    }

    public function importingInvoices()
    {
//        echo 'testing invoice import file';

        $fileName = [
//            'InvoiceHistory_102292_From_2023-03-24_To_2023-03-31.csv',
//            'InvoiceHistory_102292_From_2023-04-01_To_2023-04-30.csv',
//            'InvoiceHistory_102292_From_2023-05-01_To_2023-05-31.csv',
//            'InvoiceHistory_102292_From_2023-06-01_To_2023-06-30.csv',
//            'InvoiceHistory_102292_From_2023-07-01_To_2023-07-31.csv',
//            'InvoiceHistory_102292_From_2023-08-01_To_2023-08-31.csv',
//            'InvoiceHistory_102292_From_2023-09-02_To_2023-09-30.csv',
//            'InvoiceHistory_102292_From_2023-10-02_To_2023-10-31.csv',
//            'InvoiceHistory_102292_From_2023-11-02_To_2023-11-30.csv',
//            'InvoiceHistory_102292_From_2023-12-02_To_2023-12-31.csv',
//            'InvoiceHistory_102292_From_2024-01-01_To_2024-01-31.csv',
//            'InvoiceHistory_102292_From_2024-02-02_To_2024-02-29.csv',
//            'InvoiceHistory_102292_From_2024-03-02_To_2024-03-22.csv',
//            'InvoiceHistory_102292_From_2024-03-23_To_2024-03-29.csv',
//            'InvoiceHistory_102292_From_2024-04-01_To_2024-04-30.csv',
//            'InvoiceHistory_102292_From_2024-05-01_To_2024-05-31.csv',
//            'InvoiceHistory_102292_From_2024-06-01_To_2024-06-19.csv',
//            'InvoiceHistory_102292_From_2024-06-20_To_2024-07-21.csv',
//            'InvoiceHistory_102292_From_2024-07-21_To_2024-08-03.csv',
//            'InvoiceHistory_102292_From_2024-08-04_To_2024-08-31.csv'.
            'InvoiceHistory_102292_From_2024-09-01_To_2024-09-26.csv'
        ];

        foreach ($fileName as $file) {
            $file = self::getInvoiceFile($file);
            // Check if the file opened successfully
            if ($file !== false) {
                // Array to hold the data from the CSV file
                $csvData = [];

                // Read the file line by line until the end of file (EOF)
                while (($data = fgetcsv($file)) !== false) {
                    // Append each line's data to the array
                    $csvData[] = $data;
                }

                $invoiceNum = $csvData[1][1];

//                dd($csvData[1][1]);


//            add to scp_invoice
                self::addToScpInvoice($csvData[1]);

                for ($i = 1; $i < count($csvData); $i++) {
                    if ($csvData[$i][1] === $invoiceNum) {
//                    add to scp_invoice_item
                        self::addToScpInvoiceItem($csvData[$i], $invoiceNum);
                    } else {
                        $invoiceNum = $csvData[$i][1];
                        $i = $i - 1;
                        //            add to scp_invoice
                        self::addToScpInvoice($csvData[$i]);
                    }
                }

                // Close the file handle
                fclose($file);

                // Output the data (for demonstration purposes)
                print_r($csvData);
            }
        }

//        dd('Stop');


    }

    private function addToScpInvoice($scpInvoice)
    {


        $scpInvoiceEntry = new ScpInvoice();
        $scpInvoiceEntry->invoice_number = $scpInvoice[1];
        $scpInvoiceEntry->release_number = $scpInvoice[0];
        $scpInvoiceEntry->customer_po = $scpInvoice[2];
        $scpInvoiceEntry->status = $scpInvoice[3];

        $scpInvoiceEntry->invoice_date = self::transformDate($scpInvoice[4]);

        $scpInvoiceEntry->order_date = self::transformDate($scpInvoice[5]);

        $scpInvoiceEntry->sales_order_amount = $scpInvoice[6];
        $scpInvoiceEntry->invoice_amount = $scpInvoice[7];
        $scpInvoiceEntry->merch_amount = $scpInvoice[8];
        $scpInvoiceEntry->tax_amount = $scpInvoice[9];
        $scpInvoiceEntry->freight = $scpInvoice[10];
        $scpInvoiceEntry->other = $scpInvoice[11];

        $scpInvoiceEntry->invoice_due_date = self::transformDate($scpInvoice[12]);

        $scpInvoiceEntry->ship_to_number = $scpInvoice[13];
        $scpInvoiceEntry->ship_to_name = $scpInvoice[14];
        $scpInvoiceEntry->ship_to_address_1 = $scpInvoice[15];
        $scpInvoiceEntry->ship_to_address_2 = $scpInvoice[16];
        $scpInvoiceEntry->ship_to_address_3 = $scpInvoice[17];
        $scpInvoiceEntry->ship_to_city = $scpInvoice[18];
        $scpInvoiceEntry->ship_to_state = $scpInvoice[19];
        $scpInvoiceEntry->ship_to_postal_code = $scpInvoice[20];
        $scpInvoiceEntry->ship_to_country = $scpInvoice[21];

        $scpInvoiceEntry->order_entry_date = self::transformDate($scpInvoice[22]);

        $scpInvoiceEntry->ship_via = $scpInvoice[23];
        $scpInvoiceEntry->ar_term_code = $scpInvoice[24];
        $scpInvoiceEntry->total_qty = $scpInvoice[25];
        $scpInvoiceEntry->total_weight = $scpInvoice[26];
        $scpInvoiceEntry->tracking_numbers = $scpInvoice[27];
        $scpInvoiceEntry->inv_status = $scpInvoice[28];

        $scpInvoiceEntry->inv_bal_due = $scpInvoice[29];

        $scpInvoiceEntry->preferred_carrier = $scpInvoice[30];

        $scpInvoiceEntry->save();

    }

    private function transformDate($date)
    {

        if (strlen($date) === 10) {
            try {
                $mysqlDate = Carbon::createFromFormat('m/d/Y', $date);
                return $mysqlDate->toDateString();
            } catch (InvalidFormatException $e) {
                echo $e->getMessage();
            }
        }

        if ($date) {
            try {
                $mysqlDate = Carbon::createFromFormat('m/d/y', $date);
                return $mysqlDate->toDateString();
            } catch (InvalidFormatException $e) {
                echo $e->getMessage();
            }

        } else {
            return '';
        }
    }

    private function addToScpInvoiceItem($scpInvoice, $invoice_id)
    {
        $scpInvoiceItem = new ScpInvoiceItem();
        $scpInvoiceItem->invoice_id = $invoice_id;
        $scpInvoiceItem->units_sold = $scpInvoice[31];
        $scpInvoiceItem->product_number = $scpInvoice[32];
        $scpInvoiceItem->model_num = $scpInvoice[33];
        $scpInvoiceItem->description = $scpInvoice[34];
//        $scpInvoiceItem->customer_id = $scpInvoice[5];
//        $scpInvoiceItem->notes = $scpInvoice[6];
        $scpInvoiceItem->uom = $scpInvoice[35];
        $scpInvoiceItem->cost = $scpInvoice[36];
        $scpInvoiceItem->ext_cost = $scpInvoice[37];
        $scpInvoiceItem->ord_num = $scpInvoice[38];
        $scpInvoiceItem->line_item_num = $scpInvoice[39];
        $scpInvoiceItem->bo_qty = $scpInvoice[40];
        $scpInvoiceItem->ship_qty = $scpInvoice[41];
        $scpInvoiceItem->line_item_weight_ext = $scpInvoice[42];
        $scpInvoiceItem->ship_whse_num = $scpInvoice[43];
        $scpInvoiceItem->line_ship_via = $scpInvoice[44];
//        $scpInvoiceItem->gl_code = $scpInvoice[45];
        $scpInvoiceItem->serial_nums = $scpInvoice[45];

        $scpInvoiceItem->save();
    }

    private function getInvoiceFile($fileName)
    {
        return @fopen('/home/forge/kpspools.com/storage/files/invoices/' . $fileName, 'r+');
    }
}
