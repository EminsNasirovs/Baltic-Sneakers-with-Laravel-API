<?php
namespace App\Http\Controllers\API;

use App\Models\Favourite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavouriteController extends Controller
{
    public function index()
    {
        // Get all favourites for the authenticated user
        $favourites = Favourite::where('user_id', auth()->user()->id)->get();

        // Return the favourites as a JSON response
        return response()->json($favourites);
    }

    public function store(Request $request)
    {
        // Validate that 'sneaker_id' is provided and is an integer
        $request->validate([
            'sneaker_id' => 'required|integer|exists:sneakers,id',
        ]);

        // First, check if the favourite already exists, or create it if not
        $favourite = Favourite::firstOrCreate([
            'user_id' => auth()->id(),
            'sneaker_id' => $request->sneaker_id,
        ]);

        return response()->json($favourite);
    }

    public function destroy($id)
    {
        // Find the favourite by the 'id' and ensure it belongs to the authenticated user
        $favourite = Favourite::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Delete the favourite from the database
        $favourite->delete();

        return response()->json(['message' => 'Favourite removed successfully']);
    }
}