<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'total_price' => 'required|numeric',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $order = Order::create([
            'user_id' => $user->id, 
            'items' => $request->items,
            'total_price' => $request->total_price,
        ]);

        return response()->json($order, 201);
    }

    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        
        return response()->json(
            $user->orders()->latest()->get()
        );
    }
}
