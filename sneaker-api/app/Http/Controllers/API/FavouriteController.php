<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favourite;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    public function index()
    {
        $favourites = Favourite::where('user_id', Auth::id())->get();
        return response()->json($favourites);
    }

    public function store(Request $request)
    {
        $request->validate(['sneakerId' => 'required|integer']);
        $favourite = Favourite::create([
            'user_id' => Auth::id(),
            'sneaker_id' => $request->sneakerId,
        ]);
        return response()->json($favourite, 201);
    }

    public function destroy($id)
    {
        $favourite = Favourite::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $favourite->delete();
        return response()->json(['message' => 'Favourite removed']);
    }
}
