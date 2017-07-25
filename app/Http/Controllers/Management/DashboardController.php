<?php
namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectLocation;
use App\Models\Video;
use App\User;

use Illuminate\Http\Request;
use App\Http\Requests\CreateProjectRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $projects = Project::where('status', 'active')->count();
        $locations = ProjectLocation::where('status', 'on-going')->count();
        $livestream = Video::where('status', 'live')->count();
        $clients = User::where('user_group_id', 2)->count();

        $stats = [
            'active_projects'   => $projects,
            'active_locations'  => $locations,
            'active_livestreams' => $livestream,
            'number_of_clients' => $clients
        ];

        return view('management.index', compact('stats'));
    }
}