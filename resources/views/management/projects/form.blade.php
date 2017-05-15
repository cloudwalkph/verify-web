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
        <div class="col-md-4 hide">
            <div class="form-group">
                <label for="brand">Brand Name</label>
                <input type="text" class="form-control" id="brand" placeholder="Brand Name">
            </div>
        </div>
    </div>

    <div class="col-md-12 hide">
        <div class="col-md-4">
            <div class="form-group">
                <label for="event_type">Event Type</label>
                <select class="form-control">
                    <option value="Product Launch">Product Launch</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="ae_user">Account Executive</label>
                <select class="form-control">
                    <option value="1">Karla Cuche</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="pm_user">Project Manager</label>
                <select class="form-control">
                    <option value="1">Bonnie Clyde</option>
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-12 hide">
        <div class="col-md-12">
            <div class="form-group">
                <label for="objectives">Project Objectives</label>
                <textarea class="form-control" style="resize:none" rows="6" cols="6"></textarea>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <hr>
        <div class="col-md-6">
            <div class="form-group">
                <label for="location[name]">Areas</label>
                <input type="text" class="form-control" name="location[name]" id="name" placeholder="Areas"
                    value='{{ isset( $project->locations[0]->name ) ? $project->locations[0]->name : "" }}'>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="location[target_hits]">Target Hits</label>
                <input type="text" class="form-control" name="location[target_hits]" id="target_hits" placeholder="Target Hits"
                    value='{{ isset( $project->locations[0]->target_hits ) ? $project->locations[0]->target_hits : "" }}'>
            </div>
        </div>
        <div class="col-md-4 hide" style="margin-top: 25px">
            <div class="form-group">
                <button id="addLocation" type="button" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Add Area</button>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group">
                <label for="location[assigned_raspberry]">Assigned Raspberry</label>
                <input type="text" class="form-control" name="location[assigned_raspberry]" id="assigned_raspberry" 
                    value='{{ isset( $project->locations[0]->assigned_raspberry ) ? $project->locations[0]->assigned_raspberry : "" }}'placeholder="Assigned Raspberry">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="location[date]">Date</label>
                <input type="date" class="form-control" name="location[date]" id="target_hits" placeholder="Date"
                    value='{{ isset( $project->locations[0]->date ) ? $project->locations[0]->date : "" }}'>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-4 hide">
            <div class="form-group">
                <label for="total_runs">Total Runs</label>
                <input type="text" class="form-control" id="total_runs" placeholder="Total Runs">
            </div>
        </div>
    </div>

    <div class="col-sm-12" style="text-align: right;">
        <a href="/management" class="btn btn-danger" style="width: 200px">Cancel</a>
        <button type="submit" class="btn btn-success" style="width: 200px">Save</button>
    </div>

</div>