<?php
namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return view('management.index', compact('projects'));
    }

    public function create()
    {
        return view('management.projects.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $name = strtolower($input['name']);
        $input['slug'] = str_replace(' ', '-', $name);

        $category = Project::create($input);

        return redirect('/management');
    }

    public function edit($id)
    {
        $projects = Project::where('id', $id)->first();

        return view('management.projects.update', compact('projects'));
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        $name = strtolower($input['name']);
        $input['slug'] = str_replace(' ', '-', $name);

        $category = Category::find($id)->update($input);

        return redirect('/management');
    }
}