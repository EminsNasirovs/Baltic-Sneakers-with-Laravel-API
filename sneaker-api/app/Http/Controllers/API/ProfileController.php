<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show the authenticated user's profile.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
{
    return response()->json([
        'data' => $request->user(),
        'timestamp' => now() 
    ]);
}
public function update(Request $request)
{
    $user = $request->user();

    $validated = $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $user->id,
    ]);

    $user->update($validated);

    return response()->json([
        'message' => 'Profile updated successfully.',
        'user' => $user
    ]);
}
}
