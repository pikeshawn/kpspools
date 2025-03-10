<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AdvertisingController;

class UpdateMailingList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:mailing-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches and updates the mailing list using data from the Assessor API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
//        $this->info('Starting to update the mailing list...');

        try {
            $controller = new AdvertisingController();
//            $controller->getApnsFromArray();
            $controller->updateMailingList();
            $this->info('Mailing list updated successfully.');
        } catch (\Exception $e) {
            $this->error('Error updating mailing list: ' . $e->getMessage());
        }
    }
}
