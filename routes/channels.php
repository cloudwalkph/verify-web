<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('location.{locationId}', function ($user, $locationId) {
    $location = \App\Models\ProjectLocation::where('id', $locationId)->first();

    if (! $location) {
        return false;
    }

    $project = \App\Models\Project::where('id', $location->project_id)
        ->where('user_id', $user->id)
        ->first();

    return $project;
});
