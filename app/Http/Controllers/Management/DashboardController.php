<?php
namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\User;

use Illuminate\Http\Request;
use App\Http\Requests\CreateProjectRequest;

class DashboardController extends Controller
{
    public function index()
    {
        return view('management.index');
    }
}