<?php

namespace App\Http\Controllers;

use App\Events\FeedbackSubmitted;
use App\Http\Requests\FeedbackStore;
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

    public function store(FeedbackStore $request)
    {
        $customer                = Customer::find($request->customer_id);
        $feedback                = Feedback::create($request->all());
        $customer->last_feedback = $feedback->toArray();
        $customer->save();

        FeedbackSubmitted::dispatch($feedback);

        return $feedback;
    }

    public static function getByCustomerId(Request $request, $customerId)
    {
        return Feedback::where('customer_id', $customerId)->get();
    }
}
