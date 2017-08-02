<?php
namespace App\Http\Controllers\API\BA;

use App\Events\NewHitCreated;
use App\Http\Controllers\Controller;
use App\Models\Hit;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Events\NewFaceUploaded;

class HitsController extends Controller {

    public function createHit(Request $request, $locationId)
    {
        $input = $request->all();

        if (! $request->hasFile('file')) {
            return response()->json(['no image provided'], 400);
        }

        $hit = [
            'user_id'               => $request->user()->id,
            'project_location_id'   => $locationId,
            'hit_timestamp'         => isset($input['hit_timestamp']) ? $input['hit_timestamp'] : Carbon::now()->toDateTimeString(),
            'name'                  => isset($input['name']) ? $input['name'] : '',
            'email'                 => isset($input['email']) ? $input['email'] : '',
            'contact_number'        => isset($input['contact_number']) ? $input['contact_number'] : '',
            // 'image'                 => $input['image'],
            'location'              => isset($input['location']) ? json_encode($input['location']) : null
        ];

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $path = $request->file('file')->store($file);
        $hit['image'] = $filename;

        $newHit = Hit::create($hit);

        // foreach ($input['answers'] as $answer) {
        //     $hit['answers'] = $input['answers'];
        //     $newHit->answers()->create([
        //         'poll_id'   => $answer['poll_id'],
        //         'value'     => $answer['value']
        //     ]);
        // }

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

    public function faceUpload(Request $request, $locationId)
    {
        $user = $request->user();

        if ($request->hasFile('file')) {

            $file = $request->file('file');

            $filename = uniqid() . '-' . $locationId . '-' . $file->getClientOriginalName();
            $path = \Storage::drive('s3')->putFileAs('faces/'.$locationId, $file, $filename, 'public');
            $timestamp = $request->has('hit_timestamp') ? $request->get('hit_timestamp') : Carbon::today()->toDateTimeString();

            // Queue the image processing
            event(new NewFaceUploaded($path, $locationId, $timestamp, $user->id));

            return response()->json(['success'], 201);
        }

        return response()->json(['error'], 400);
    }
}