<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Feedback;
use App\Models\Order;
use Illuminate\Console\Command;

class SetCustomerLastOrderFeedback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:set-last-order-feedback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets the last order and feedback field on the customer records.';

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
        /** @var Customer[] $customers */
        $customers = Customer::all();
        foreach ($customers as $customer) {
            $lastOrder    = Order::where('email', $customer->email)->orderBy('created_at', 'DESC')->first();
            $lastFeedback = Feedback::where('customer_id', $customer->id)->orderBy('created_at', 'DESC')->first();

            $customer->last_order    = $lastOrder ? $lastOrder->toArray() : null;
            $customer->last_feedback = $lastFeedback ? $lastFeedback->toArray() : null;

            /** @var Feedback[] $feedbacks */
            $feedbacks = Feedback::where('customer_id', $customer->id)->get();

            $total = 0;
            foreach ($feedbacks as $feedback) {
                $total += $feedback->antigen_tests_administered;
            }
            $customer->total_administered = $total;
            $customer->save();
            $this->info(sprintf('Customer: %s updated', $customer->id));
        }

        return 0;
    }
}
