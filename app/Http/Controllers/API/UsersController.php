<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserLocation;
use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller {
    public function profile(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['unauthorized'], 401);
        }

        $user = User::with('profile')->where('id', $user->id)
            ->first();

        return response()->json($user, 200);
    }

    public function createLocationRecord(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['unauthorized'], 401);
        }

        $input = $request->all();
        $location = UserLocation::create([
            'user_id'   => $user->id,
            'lat'       => $input['lat'],
            'lng'       => $input['lng'],
            'created_at'    => $input['created_at'],
        ]);

        if (! $location) {
            return response()->json(['failed to create location'], 400);
        }

        return response()->json($location, 200);
    }
}