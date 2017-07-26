<?php
namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\UserLocation;
use App\User;
use App\Models\UserGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\CreateAccountRequest;

class AccountsController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('management.admin.accounts.index', compact('users'));
    }

    public function create()
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

    public function importGPSData(Request $request)
    {
        if (! $request->hasFile('gps_file')) {
            $request->session()->flash('error', 'No file to upload');
            return redirect()->back();
        }
        
        $results = [];
        \Excel::load($request->file('gps_file'), function($reader) use (&$results, $request) {
            // reader methods
            $sheets = $reader->all();
            foreach ($sheets->toArray() as $sheet) {
                foreach ($sheet as $location) {
                    $latlng = explode(',', $location['llc']);
                    $data = [
                        'user_id'   => $request->get('user_id'),
                        'lat'   => $latlng[0],
                        'lng'   => $latlng[1],
                        'created_at' => Carbon::createFromTimestamp(strtotime($location['time']))->toDateTimeString()
                    ];
                    $results[] = $data;
                    // Create Data
                    UserLocation::create($data);
                }
            }
        });
        return redirect()->back();
    }

    public function getClients(Request $request)
    {
        $query = $request->get('q');

        $users = User::with('profile')->where('user_group_id', $request->get('group'))
            ->whereHas('profile', function($q) use ($query) {
                $q->where('first_name', 'LIKE', '%'.$query.'%');
            })
            ->limit(10)
            ->get();

        $result = [];
        foreach ($users as $user) {
            $result[] = [
                'id'    => $user->id,
                'name'  => $user->profile->full_name
            ];
        }

        return response()->json($result, 200);
    }
}