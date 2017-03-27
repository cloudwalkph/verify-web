<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class EventsController extends Controller {
    public function self(Request $request)
    {
        $user = $request->user();

        $events = Project::with('locations')->where('user_id', $user->id)
            ->where('status', 'active')
            ->get();

        return response()->json($events, 200);
    }
}