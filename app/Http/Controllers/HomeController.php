<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\ProjectShare;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $projects = Project::where('user_id', $user->id)
            ->get();

        $sharedProjects = ProjectShare::with('project')
            ->where('user_id', $user->id)
            ->get();

        return view('home', compact('projects', 'sharedProjects'));
    }
}
