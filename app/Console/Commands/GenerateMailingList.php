<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use League\Csv\Writer;

class GenerateMailingList extends Command
{
    protected $signature = 'app:mailing:list';

    protected $description = 'Generate a mailing list, excluding existing customers';

    public function handle()
    {
        // File paths
        $rawFilePath = storage_path('app/30000_03_17_2025.csv');
        $sentFilePath = storage_path('app/30000_03_17_2025_sent.csv');

        // Ensure the file exists
        if (! file_exists($rawFilePath)) {
            $this->error("Raw file not found: $rawFilePath");

            return;
        }

        // Load the raw names CSV
        $csv = Reader::createFromPath($rawFilePath, 'r');
        $csv->setHeaderOffset(0); // First row is the header

        $records = iterator_to_array($csv->getRecords());

        if (empty($records)) {
            $this->info('No records found in raw file.');

            return;
        }

        // Fetch existing addresses from the database
        $existingAddresses = DB::table('addresses')->pluck('address_line_1')->toArray();

        $unmatchedRecords = [];

        foreach ($records as $row) {
            if (! in_array($row['mailing_address_line_1'], $existingAddresses)) {
                $unmatchedRecords[] = [
                    $row['name'],
                    $row['mailing_address_line_1'],
                    $row['mailing_city'],
                    $row['mailing_state'],
                    $row['mailing_zip'],
                ];
            }
        }

        if (empty($unmatchedRecords)) {
            $this->info('All addresses matched. No new entries found.');

            return;
        }

        // Write to the output file
        $writer = Writer::createFromPath($sentFilePath, 'w+');
        $writer->insertOne(['Name', 'Address', 'City', 'State', 'Zip']); // Header
        $writer->insertAll($unmatchedRecords);

        $this->info(count($unmatchedRecords)." unmatched addresses written to $sentFilePath.");
    }
}
