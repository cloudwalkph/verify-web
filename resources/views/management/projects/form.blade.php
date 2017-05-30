<div class="row">
    <div class="col-md-12">
        @include('components.errors')
        @include('components.success')
    </div>

    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Project Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Project Name"
                    value='{{ isset( $project->name ) ? $project->name : "" }}'>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="user_id">Client Name</label>
                <select class="form-control" name="user_id">
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->profile->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

    <div class="col-md-12">
        <legend>Locations</legend>
    </div>

    <div class="locations col-md-12">
        <div class="location-item col-md-12">
            <div class="col-md-12">
                <button class="btn btn-primary pull-right add-location" type="button">Add Location</button>
            </div>

            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="location[name]">Location</label>
                        <input type="text" class="form-control input-field" name="locations[0][name]" id="name" placeholder="Location"
                               value='{{ isset( $project->locations[0]->name ) ? $project->locations[0]->name : "" }}'>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="location[target_hits]">Target Hits</label>
                        <input type="text" class="form-control input-field" name="locations[0][target_hits]" id="target_hits" placeholder="Target Hits"
                               value='{{ isset( $project->locations[0]->target_hits ) ? $project->locations[0]->target_hits : "" }}'>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="location[assigned_raspberry]">Assigned VBox (For livestreaming)</label>
                        <select class="form-control input-field" name="locations[0][assigned_raspberry]" id="assigned_raspberry" >
                            <option value="0" selected>No livestreaming</option>
                            @for($i = 1; $i <= 25; $i++)
                                <option value="raspi-{{$i}}">V-BOX {{$i}}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="location[video_name]">Video Name</label>
                        <input type="text" class="form-control input-field" id="video_name" placeholder="Video Name"
                               value='{{ isset( $project->locations[0]->video_name ) ? $project->locations[0]->video_name : "" }}'>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="location[date]">Date</label>
                        <input type="date" class="form-control input-field" name="locations[0][date]" id="target_hits" placeholder="Date"
                               value='{{ isset( $project->locations[0]->date ) ? $project->locations[0]->date : "" }}'>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Services</label> <br>
                        <label><input type="checkbox" value="manual" class="checkbox-input" name="locations[0][services][]"> Manual V-App</label>
                        <label><input type="checkbox" value="automatic" class="checkbox-input" name="locations[0][services][]"> Automatic V-App</label>
                        <label><input type="checkbox" value="gps" class="checkbox-input" name="locations[0][services][]"> GPS Tracker</label>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="col-sm-12" style="text-align: right;">
        <a href="/management" class="btn btn-danger" style="width: 200px">Cancel</a>
        <button type="submit" class="btn btn-success" style="width: 200px">Save</button>
    </div>

</div>