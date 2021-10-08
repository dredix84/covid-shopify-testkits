<?php

namespace App\Http\Controllers;

use App\Helpers\Util;
use App\Models\Order;
use App\Models\PickupLocation;
use App\Models\Receive;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user            = auth()->user();
        $pickupLocations = $user->is_admin
            ? PickupLocation::list()->get()
            : PickupLocation::list()->whereIn('_id', $user->pickup_locations)->get();

        return Inertia::render(
            'Orders',
            [
                'init-data' => [
                    'orders'  => $this->getOrders($request),
                    'options' => [
                        'pickup_locations' => $pickupLocations
                    ]
                ]
            ]
        );
    }

    public function getOrders(Request $request)
    {
        $cacheKey = 'GetOrders_'.Util::getObjectHash($request->all());
        $orders   = Cache::get($cacheKey);
        if ($orders === null) {
            $per_page = $request->per_page ?? 25;
            $orders   = Order::orderBy('order_number', 'desc')
                ->filter($request->all())
                ->mine()
                ->paginate((int) $per_page);
            Cache::put($cacheKey, $orders, 60);
        }

        return $orders;
    }


}
