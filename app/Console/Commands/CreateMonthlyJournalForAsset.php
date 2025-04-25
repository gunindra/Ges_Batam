<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;
use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\JournalController;
use Carbon\Carbon;
use Log;

class CreateMonthlyJournalForAsset extends Command
{
    protected $signature = 'journal:create-monthly';
    protected $description = 'Create monthly journal entries for assets until their estimated age is reached';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Instantiate the controller
        $controller = app()->make(\App\Http\Controllers\Admin\AssetController::class); // Adjust with the actual namespace

        // Get assets that need journal entries
        $assets = Asset::all(); // Modify this query to get only assets that need a journal entry
        Log::info("ASETTTTTTTTTTTTTTTTTTTTTTTTTT");
        foreach ($assets as $asset) {
            // Calculate the asset's age in months since acquisition
            $monthsElapsed = Carbon::parse($asset->acquisition_date)->diffInMonths(Carbon::now());

            // Check if the asset is still within its estimated age
            if ($monthsElapsed < $asset->estimated_age) {
                // Create a request object to pass to the function
                $request = request(); // Adjust as necessary to pass required data
                $controller->createJournalForDepreciation($request, $asset);
            }
        }

        $this->info('Monthly journals created for eligible assets.');
    }
}
