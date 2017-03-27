<?php
namespace App\Http\Controllers\API\BA;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectLocation;
use Illuminate\Http\Request;

class ProjectsController extends Controller {
    public function self(Request $request)
    {
        $user = $request->user();

        // Iterate locations
        $events = Project::with(['locations' => function($q) use ($user) {
                $q->whereHas('users', function($locations) use ($user) {
                    $locations->where('user_id', $user->id)
                        ->where('status', '!=', 'completed');
                });
            }])
            ->whereHas('locations', function($q) use ($user) {
                $q->whereHas('users', function($locations) use ($user) {
                    $locations->where('user_id', $user->id)
                        ->where('status', '!=', 'completed');
                });
            })
            ->where('status', 'active')
            ->get();

        return response()->json($events, 200);
    }
}