<?php
namespace App\Http\Controllers\API\BA;

use App\Http\Controllers\Controller;
use App\Models\UserLocation;
use Illuminate\Http\Request;

class UserLocationController extends Controller {

    public function saveLocation(Request $request, $locationId)
    {
        $input = $request->all();

        $user_location = [
            'user_id'               => $request->user()->id,
            'project_location_id'   => $locationId,
            'lat'                   => isset($input['lat']) ? $input['lat'] : '',
            'lng'                   => isset($input['lng']) ? $input['lng'] : ''
        ];

        $userlocation = UserLocation::create($user_location);

        return response()->json($userlocation, 200);
    }

    public function self($locationId)
    {
        $userlocation = UserLocation::where('project_location_id', $locationId)
            ->get();

        return response()->json($userlocation, 200);
    }
}