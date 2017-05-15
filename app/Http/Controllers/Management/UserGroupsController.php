<?php
namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use App\Http\Requests\CreateUserGroupRequest;

class UserGroupsController extends Controller
{
    public function index()
    {
        return view('management.admin.user-groups.create');
    }

    public function store(CreateUserGroupRequest $request)
    {
        $input = $request->all();

        $user_group = UserGroup::create($input);

        return redirect()->back()->with('status', 'Successfully added new user group');
    }

    public function edit($id)
    {
        $user_group = UserGroup::where('id', $id)->first();

        return view('management.admin.user-groups.update', compact('user_group'));
    }

    public function update(CreateUserGroupRequest $request, $id)
    {
        $input = $request->all();

        $user_group = UserGroup::find($id)->update($input);

        return redirect()->back()->with('status', 'Successfully updated user group details');
    }
}