<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render(
            'Customers',
            [
                'init-data'       => [
                    'customers' => $this->getCustomers($request),
                ],
                'shipping-titles' => Order::getShippingTitles()
            ]
        );
    }

    public function getCustomers(Request $request)
    {
        $per_page      = $request->per_page ?? 25;
        $filters       = $request->filters ? json_decode($request->filters) : null;
        $searchTerm    = $filters->searchTerm ?? null;
        $shippingTitle = $filters->shippingTitle ?? null;

        return Customer::orderBy('created_at', 'desc')
            ->where(function ($q) use ($searchTerm) {
                if (!blank($searchTerm)) {
                    $q->where('first_name', 'like', "%$searchTerm%");
                    $q->orWhere('last_name', 'like', "%$searchTerm%");
                    $q->orWhere('email', 'like', "%$searchTerm%");
                }
            })
            ->where(function ($q) use ($shippingTitle) {
                if (!blank($shippingTitle)) {
                    $q->where('last_order.shipping_lines.title', $shippingTitle);
                }
            })
//            ->where(function ($q) use ($filters) {
//                if ($filters) {
//                    foreach ($filters as $key => $value) {
//                        if ($value !== null) {
//                            $q->where($key, $value);
//                        }
//                    }
//                }
//
//            })
            ->paginate((int) $per_page);
    }
}
