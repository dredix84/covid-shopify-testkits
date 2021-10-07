<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PickupLocation;
use App\Models\Receive;
use App\Models\User;
use Illuminate\Http\Request;
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
        $per_page = $request->per_page ?? 25;
        $filters  = $request->filters ? json_decode($request->filters) : null;
        /** @var User $user */
        $user = auth()->user();

        return Order::orderBy('order_number', 'desc')
            ->filter($request->all())
            ->where(function ($q) use ($user) {
                if (!$user->is_admin) {
                    $q->whereIn('pickup_location', $user->getPickupLocationName());
                }
            })
            /*->where(function ($q) use ($filters) {
                if ($filters) {
                    foreach ($filters as $key => $value) {
                        if (!blank($value)) {
                            $q->where($key, $value);
                        }
                    }
                }
            })*/
            ->paginate((int) $per_page);
    }
}
