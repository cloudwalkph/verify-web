<?php
namespace App\Http\Controllers\API\BA;

use App\Events\NewHitCreated;
use App\Http\Controllers\Controller;
use App\Models\Hit;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HitsController extends Controller {

    public function createHit(Request $request, $projectId, $locationId)
    {
        if (! $request->hasFile('image')) {
            return response()->json('no image found', 400);
        }

        $input = $request->all();

        // Save Image
        $filename = uniqid().'.jpeg';
        $path = $request->file('image')->storeAs('public', $filename);

        $hit = [
            'user_id'               => $request->user()->id,
            'project_location_id'   => $locationId,
            'hit_timestamp'         => isset($input['hit_timestamp']) ? $input['hit_timestamp'] : Carbon::now()->toDateTimeString(),
            'name'                  => isset($input['name']) ? $input['name'] : '',
            'email'                 => isset($input['email']) ? $input['email'] : '',
            'contact_number'        => isset($input['contact_number']) ? $input['contact_number'] : '',
            'image'                 => $filename,
            'location'              => isset($input['location']) ? json_encode($input['location']) : null
        ];

        $newHit = Hit::create($hit);

        foreach (json_decode($input['answers']) as $answer) {
            $hit['answers'] = $input['answers'];
            $newHit->answers()->create([
                'poll_id'   => $answer['poll_id'],
                'value'     => $answer['value']
            ]);
        }

        if ($newHit) {
            event(new NewHitCreated($hit));
        }

        return response()->json($newHit, 200);
    }

    public function updateImage(Request $request, $hitId)
    {
        \Log::info($request);
        \Log::info($request->all());
        if (! $request->hasFile('image')) {
            return response()->json('no image found', 400);
        }

        $filename = uniqid().'.jpeg';
        $path = $request->file('image')->storeAs('public', $filename);

        $hit = Hit::where('id', $hitId)
            ->update([
                'image' => $filename
            ]);

        return response()->json($hit);
    }

    public function getHitsByLocation($locationId)
    {
        $hits = Hit::with('answers')
            ->where('project_location_id', $locationId)
            ->get();

        return response()->json($hits, 200);
    }
}