<?php

namespace App\Http\Controllers;

use App\Models\Receive;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReceiverController extends Controller
{
    public function receiving(Request $request)
    {
        $received          = new Receive();
        $received->header = $request->header();
        $received->payload = $request->all();

        $received->save();

        return response()->json(['message' => 'received'], 200);
    }
}
