<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $newOrders      = Order::where('financial_status', 'paid')
            ->count();
        $ordersThisWeek = Order::where('created_at', '>', Carbon::now()->startOfWeek())
            ->where('created_at', '<', Carbon::now()->endOfWeek())
            ->count();
        $customers = Customer::count();

        return Inertia::render(
            'Dashboard',
            [
                'counts' => compact('newOrders', 'ordersThisWeek', 'customers')
            ]
        );
    }
}
