<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render(
            'Customers',
            [
                'init-data' => [
                    'customers' => $this->getCustomers($request)
                ]
            ]
        );
    }

    public function getCustomers(Request $request)
    {
        $per_page = $request->per_page ?? 25;
        $filters  = $request->filters ? json_decode($request->filters) : null;

        return Customer::orderBy('created_at', 'desc')
            ->with(['LastFeedback', 'LastOrder'])
            ->where(function ($q) use ($filters) {
                if ($filters) {
                    foreach ($filters as $key => $value) {
                        if ($value !== null) {
                            $q->where($key, $value);
                        }
                    }
                }

            })
            ->paginate((int) $per_page);
    }
}
