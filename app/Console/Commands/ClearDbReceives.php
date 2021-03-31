<?php

namespace App\Console\Commands;

use App\Models\Receive;
use Illuminate\Console\Command;

class ClearDbReceives extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:receive:truncate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncates all receive records';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Receive::truncate();
        return 0;
    }
}
