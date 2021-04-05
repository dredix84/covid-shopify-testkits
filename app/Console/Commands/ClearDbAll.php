<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Receive;
use Illuminate\Console\Command;

class ClearDbAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:all:truncate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncates all receive, customer and order records';

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
        $this->info('Clearing collections for receives, orders and customers');
        Receive::truncate();
        Order::truncate();
        Customer::truncate();
        $this->info('Operation completed');
        return 0;
    }
}
