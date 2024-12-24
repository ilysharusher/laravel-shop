<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class FreshMigrationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fresh migrations and seeders';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (app()->isLocal()) {
            Storage::deleteDirectory('images');

            Cache::flush();

            $this->call('migrate:fresh', ['--seed' => true]);
        } else {
            $this->error('This command can only be run in the local environment.');
        }
    }
}
