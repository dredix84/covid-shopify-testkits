<?php

namespace App\Http\Controllers;

use App\Events\ReceivedOrder;
use App\Models\Customer;
use App\Models\Receive;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReceiverController extends Controller
{
    public function receiving(Request $request): \Illuminate\Http\JsonResponse
    {
        $received = new Receive();
        $received->fillHeaders($request->header());
        $received->payload = $request->all();
        $received->save();

        ReceivedOrder::dispatch($received);

        return response()->json(['message' => 'received'], 200);
    }
}
