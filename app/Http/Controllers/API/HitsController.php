<?php
namespace App\Http\Controllers\API;

use App\Events\NewHitCreated;
use App\Http\Controllers\Controller;
use App\Models\Hit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HitsController extends Controller {
    public function createHit(Request $request, $locationId)
    {
        $input = $request->all();

        $newHit = null;
        \DB::transaction(function() use ($input, $locationId, &$newHit) {
            $hit = [
                'user_id'               => 1,
                'project_location_id'   => $locationId,
                'hit_timestamp'         => isset($input['hit_timestamp']) ? $input['hit_timestamp'] : Carbon::now()->toDateTimeString(),
                'name'                  => isset($input['name']) ? $input['name'] : '',
                'email'                 => isset($input['email']) ? $input['email'] : '',
                'contact_number'        => isset($input['contact_number']) ? $input['contact_number'] : '',
                'location'              => isset($input['location']) ? json_encode($input['location']) : null
            ];

            $newHit = Hit::create($hit);

            // Insert Age Group
            $newHit->answers()->create([
                'poll_id'   => 1,
                'value'     => $input['age_group']
            ]);

            // Insert Gender
            $newHit->answers()->create([
                'poll_id'   => 2,
                'value'     => $input['gender']
            ]);

            if ($newHit) {
                event(new NewHitCreated($hit));
            }
        });



        return response()->json($newHit, 200);
    }
}