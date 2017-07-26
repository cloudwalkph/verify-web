<div class="row">
    <div class="col-md-12">
        @include('components.errors')
    </div>

    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Project Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Project Name"
                       value='{{ isset( $project->name ) ? $project->name : old('name') }}'>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="user_id">Client Name</label>
                <select class="form-control" id="user_id" name="user_id">
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('user_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->profile->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="col-sm-12" style="text-align: right;">
        <a href="/management" class="btn btn-danger" style="width: 200px">Cancel</a>
        <button type="submit" class="btn btn-primary" style="width: 200px">Save</button>
    </div>

</div>