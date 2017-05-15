<?php
namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use App\Http\Requests\CreateAccountRequest;

class AccountsController extends Controller
{
    public function index()
    {
    	$groups = UserGroup::all();

        return view('management.admin.accounts.create', compact('groups'));
    }

    public function store(CreateAccountRequest $request)
    {
        $input = $request->all();

        $profile = $input['profile'];
        unset($input['profile']);
        unset($input['password_confirmation']);

        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        $user->profile()->create($profile);

        return redirect()->back()->with('status', 'Successfully added new account');
    }

    public function edit($id)
    {
    	$groups = UserGroup::all();
        $user = User::where('id', $id)->first();

        return view('management.admin.accounts.update', compact('user', 'groups'));
    }

    public function update(CreateAccountRequest $request, $id)
    {
        $input = $request->all();

        $profile = $input['profile'];
        unset($input['profile']);
        unset($input['password_confirmation']);

        $input['password'] = bcrypt($input['password']);

        $user = User::find($id)->update($input);
        $user = User::where('id', $id)->first();

        $user->profile()->update($profile);

        return redirect()->back()->with('status', 'Successfully updated account details');
    }
}