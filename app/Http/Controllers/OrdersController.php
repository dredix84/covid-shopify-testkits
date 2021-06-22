<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PickupLocation;
use App\Models\Receive;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render(
            'Orders',
            [
                'init-data' => [
                    'orders'  => $this->getOrders($request),
                    'options' => [
                        'pickup_locations' => PickupLocation::list()->get()
                    ]
                ]
            ]
        );
    }

    public function getOrders(Request $request)
    {
        $per_page = $request->per_page ?? 25;
        $filters  = $request->filters ? json_decode($request->filters) : null;

        return Order::orderBy('order_number', 'desc')
            ->where(function ($q) use ($filters) {
                if ($filters) {
                    foreach ($filters as $key => $value) {
                        if (!blank($value)) {
                            $q->where($key, $value);
                        }
                    }
                }
            })
            ->paginate((int) $per_page);
    }
}
