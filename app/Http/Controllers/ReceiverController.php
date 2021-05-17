<?php

namespace App\Http\Controllers;

use App\Events\ReceivedOrder;
use App\Helpers\Util;
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

        if (in_array($received->topic, Util::isAllowedHeader())) {
            $received->payload = $request->all();
            $received->save();

            ReceivedOrder::dispatch($received);
            return response()->json(['message' => 'received'], 201);
        }

        return response()->json(['message' => 'received'], 200);
    }
}
