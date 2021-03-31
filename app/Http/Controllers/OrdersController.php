<?php

namespace App\Http\Controllers;

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
        return Receive::all();
    }
}
