<?php
namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\UserGroup;
use App\Models\Brand;
use Illuminate\Http\Request;

use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $brands = Brand::all();
        $user_groups = UserGroup::all();
        $accounts = User::all();

        return view('management.admin.index', 
        	compact('user', 'accounts', 'brands', 'user_groups'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $input = $request->all();
        $user = User::where('id', $user->id)->first();

        $profile = $input['profile'];
        unset($input['profile']);

        if($input['old_password'] != "" && $input['password'] != "") {
        	if (\Hash::check($input['old_password'], $user->password)) {
	            $user->update(['password' => bcrypt($input['password']) ]);
	        }else {
	            return redirect()->back()->withErrors(array('old_password' => "Old Password does not match."));
	        }
        }

        $user->profile()->update($profile);

        return redirect()->back()->with('status', 'Successfully updated profile');
    }
}