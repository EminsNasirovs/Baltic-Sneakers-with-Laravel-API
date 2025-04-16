<?php


namespace App\Http\Controllers\API;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        return Order::where('user_id', Auth::id())->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'total_price' => 'required|integer',
        ]);

        return Order::create([
            'user_id' => Auth::id(),
            'items' => $request->items,
            'total_price' => $request->total_price,
        ]);
    }
}
