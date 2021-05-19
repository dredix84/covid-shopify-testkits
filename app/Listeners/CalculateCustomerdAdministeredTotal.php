<?php

namespace App\Listeners;

use App\Events\FeedbackSubmitted;
use App\Models\Customer;
use App\Models\Feedback;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CalculateCustomerdAdministeredTotal
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  FeedbackSubmitted  $event
     * @return void
     */
    public function handle(FeedbackSubmitted $event)
    {
        /** @var Feedback[] $feedbacks */
        $feedbacks = Feedback::where('customer_id', $event->feedback->customer_id)->get();

        $total = 0;
        foreach ($feedbacks as $feedback) {
            $total += $feedback->antigen_tests_administered;
        }
        /** @var Customer $customer */
        $customer                     = Customer::find($event->feedback->customer_id);
        $customer->total_administered = $total;
        $customer->save();
    }
}
