<?php

namespace App\Console\Commands;

use App\Models\Address;
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
        self::importingInvoices();
//        self::setCorrectCountForTasks();
//        self::associateTasksWithServicemean();
    }

    private function setCorrectCountForTasks()
    {
        $addresses = Task::select(['address_id'])->distinct()->get();

//        dd($addresses[0]->address_id);

        foreach($addresses as $address) {
//            dd($address->address_id);
            $tasks = Task::where('address_id', $address->address_id)->get();
            $count = 0;
            foreach($tasks as $task) {
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
                ->where('active', true)->get();
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
                ->where('active', true)->get();
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
                ->where('active', true)->get();
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
            'InvoiceHistory_102292_From_2023-03-24_To_2023-03-31.csv',
            'InvoiceHistory_102292_From_2023-04-01_To_2023-04-30.csv',
            'InvoiceHistory_102292_From_2023-05-01_To_2023-05-31.csv',
            'InvoiceHistory_102292_From_2023-06-01_To_2023-06-30.csv',
            'InvoiceHistory_102292_From_2023-07-01_To_2023-07-31.csv',
            'InvoiceHistory_102292_From_2023-08-01_To_2023-08-31.csv',
            'InvoiceHistory_102292_From_2023-09-02_To_2023-09-30.csv',
            'InvoiceHistory_102292_From_2023-10-02_To_2023-10-31.csv',
            'InvoiceHistory_102292_From_2023-11-02_To_2023-11-30.csv',
            'InvoiceHistory_102292_From_2023-12-02_To_2023-12-31.csv',
            'InvoiceHistory_102292_From_2024-01-01_To_2024-01-31.csv',
            'InvoiceHistory_102292_From_2024-02-02_To_2024-02-29.csv',
            'InvoiceHistory_102292_From_2024-03-02_To_2024-03-22.csv',
            'InvoiceHistory_102292_From_2024-03-23_To_2024-03-29.csv',
            'InvoiceHistory_102292_From_2024-04-01_To_2024-04-30.csv',
            'InvoiceHistory_102292_From_2024-05-01_To_2024-05-31.csv',
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

        if (strlen($date) === 10){
            try {
                $mysqlDate = Carbon::createFromFormat('m/d/Y', $date);
                return $mysqlDate->toDateString();
            } catch (InvalidFormatException $e){
                echo $e->getMessage();
            }
        }

        if ($date) {
            try {
                $mysqlDate = Carbon::createFromFormat('m/d/y', $date);
                return $mysqlDate->toDateString();
            } catch (InvalidFormatException $e){
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
