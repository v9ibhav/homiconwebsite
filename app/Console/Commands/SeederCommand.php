<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SeederCommand extends Command
{
    protected $signature = 'seeder:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs migrate fresh, seeds the database, and dispatches a job every 6 hours';

    /**
     * Execute the console command.
     */
    public function handle()
     {
         // Running the migrate:fresh --seed command
         $this->info('Running database migrations...');
         Artisan::call('migrate:fresh --seed');
         $this->info('Database migrations completed.');
     
         // Set permissions for storage and cache directories
         $this->info('Setting directory permissions...');
         $output = shell_exec('sudo chgrp -R www-data storage bootstrap/cache');
         $this->info($output);
     
         $output = shell_exec('sudo chmod -R ug+rwx storage bootstrap/cache');
         $this->info($output);
     
         $this->info('All tasks completed successfully!');
     }
}
