<?php

namespace App\Http\Controllers;

use App\Events\FeedbackSubmitted;
use App\Models\Customer;
use App\Models\Feedback;
use Cassandra\Custom;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FeedbackController extends Controller
{
    public function showForm(Request $request, $customerId)
    {
        $customer = Customer::where('_id', $customerId)
            ->orWhere("shopify_id", (int) $customerId)
            ->first();
        return Inertia::render(
            'OrderFeedback',
            [
                'customer' => $customer
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_count'         => 'numeric|min:1',
            'employee_participating' => 'numeric'
        ]);

        $customer                = Customer::find($request->customer_id);
        $feedback                = Feedback::create($request->all());
        $customer->last_feedback = $feedback->toArray();
        $customer->save();

        FeedbackSubmitted::dispatch($feedback);

        return $feedback;
    }
}
