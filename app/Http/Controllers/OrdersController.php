<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Receive;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrdersController extends Controller
{
    public function index()
    {
        return Inertia::render('Orders');
    }

    public function getOrders(Request $request)
    {
        $per_page = $request->per_page ?? 25;
        $filters  = $request->filters ? json_decode($request->filters) : null;

        return Order::orderBy('created_at', 'desc')
            ->where(function ($q) use ($filters) {
                foreach ($filters as $key => $value) {
                    if ($value !== null) {
                        $q->where($key, $value);
                    }
                }
            })
            ->paginate((int) $per_page);
    }
}
