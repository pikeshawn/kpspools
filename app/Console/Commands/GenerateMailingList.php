<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class GenerateMailingList extends Command
{
    protected $signature = 'app:mailing:list';
    protected $description = 'Generate a mailing list, excluding existing customers';

    public function handle()
    {
        $this->info("Fetching owner addresses...");

        // Fetch all customer addresses and normalize them
        $customerAddresses = DB::table('addresses')
            ->pluck('address_line_1')
            ->map(fn($address) => $this->normalizeAddress($address))
            ->flip();

        // Open CSV writer
        $filePath = storage_path('app/filtered_mailing_list.csv');
        $csv = Writer::createFromPath($filePath, 'w+');
        $csv->insertOne(['Name', 'Address', 'City', 'State', 'Zip']);

        // Track removed entries
        $removedEntries = [];

        // Process owners in chunks to handle large datasets
        DB::table('owners')
            ->whereNotNull('section_township_range')
            ->orderBy('id')
            ->chunk(1000, function ($owners) use ($csv, $customerAddresses, &$removedEntries) {
                foreach ($owners as $owner) {
                    $normalizedAddress = $this->normalizeAddress($owner->address_line_1);

                    if (isset($customerAddresses[$normalizedAddress])) {
                        $removedEntries[] = $owner->address_line_1;
                        continue;
                    }

                    $csv->insertOne([
                        $owner->name,
                        $owner->address_line_1,
                        $owner->city,
                        $owner->state,
                        $owner->zip
                    ]);
                }
            });

        // Log removed entries
        Storage::put('removed_addresses.log', implode("\n", $removedEntries));

        $this->info("Mailing list generated successfully: storage/app/filtered_mailing_list.csv");
        $this->info("Removed addresses logged in: storage/app/removed_addresses.log");
    }

    /**
     * Normalize addresses for consistent comparison.
     */
    private function normalizeAddress($address)
    {
        return strtolower(trim(str_replace(
            [' street', ' avenue', ' road', ' boulevard', ' drive'],
            [' st', ' ave', ' rd', ' blvd', ' dr'],
            $address
        )));
    }
}
