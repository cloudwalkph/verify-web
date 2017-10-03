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
        <div class="col-md-6" style="margin-bottom: 20px">
            <div class="control-group">
                <label for="user_id">Client Name</label>
                <select class="form-control" id="user_id" name="user_id">
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="target_runs">Target Runs</label>
                <input type="number" id="target_runs"
                       name="total_target_runs" class="form-control"/>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="target_hits">Target Hits</label>
                <input type="number" id="target_hits"
                       name="total_target_hits" class="form-control"/>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="brands">Brands</label>
                <input type="text" id="brands" name="brands" class="form-control"
                       value='{{ isset( $project->brands ) ? $project->brands : '' }}'/>
            </div>
        </div>
    </div>

    <div class="col-sm-12" style="text-align: right;">
        <a href="/management" class="btn btn-danger" style="width: 200px">Cancel</a>
        <button type="submit" class="btn btn-primary" style="width: 200px">Save</button>
    </div>

</div>