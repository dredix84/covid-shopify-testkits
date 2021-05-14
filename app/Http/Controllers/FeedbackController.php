<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FeedbackController extends Controller
{
    public function showForm(Request $request, $customerId)
    {
        $customer = Customer::findOrFail($customerId);
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

        $feedback = Feedback::create($request->all());

        return $feedback;
    }
}
