<?php

namespace App\Console\Commands;

use App\Events\ReceivedOrder;
use App\Models\Receive;
use Illuminate\Console\Command;

class ReprocessPayload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payload:process {payloadId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set payload to be reprocessed';

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
        $payloadId = $this->argument('payloadId');
        if (is_numeric($payloadId)) {
            $received = Receive::where("payload.order_number", (int) $payloadId)->first();
        } else {
            $received = Receive::find($payloadId);
        }

        if ($received) {
//            dd($received->toArray());
            ReceivedOrder::dispatch($received);
        } else {
            $this->warn("No records found with ID: $payloadId");
        }

        return 0;
    }
}
