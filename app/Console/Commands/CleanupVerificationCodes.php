<?php

namespace App\Console\Commands;

use App\Models\VerificationCode;
use Illuminate\Console\Command;

class CleanupVerificationCodes extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'verification:cleanup {--days=30 : Delete verification codes older than this many days}';

    /**
     * The console command description.
     */
    protected $description = 'Clean up expired and used verification codes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');

        $this->info('Cleaning up verification codes...');

        $count = VerificationCode::cleanup($days);

        $this->info("Successfully deleted {$count} expired verification codes.");

        return Command::SUCCESS;
    }
}
