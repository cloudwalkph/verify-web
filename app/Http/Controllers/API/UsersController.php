<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
}