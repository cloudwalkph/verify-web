<div class="row">
    @include('components.errors')

    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Project Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Project Name">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="client">Client Name</label>
                <input type="text" class="form-control" name="client" id="client" placeholder="Client Name">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="brand">Brand Name</label>
                <input type="text" class="form-control" name="brand" id="brand" placeholder="Brand Name">
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label for="event_type">Event Type</label>
                <select class="form-control" name="event_type">
                    <option value="Product Launch">Product Launch</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="ae_user">Account Executive</label>
                <select class="form-control" name="ae_user">
                    <option value="1">Karla Cuche</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="pm_user">Project Manager</label>
                <select class="form-control" name="pm_user">
                    <option value="1">Bonnie Clyde</option>
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-12">
            <div class="form-group">
                <label for="objectives">Project Objectives</label>
                <textarea class="form-control" style="resize:none" name="objectives" rows="6" cols="6"></textarea>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label for="areas">Areas</label>
                <input type="text" class="form-control" name="areas" id="areas" placeholder="Areas">
            </div>
        </div>
        <div class="col-md-4" style="margin-top: 25px">
            <div class="form-group">
                <button type="button" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Add Area</button>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label for="total_runs">Total Runs</label>
                <input type="text" class="form-control" name="total_runs" id="total_runs" placeholder="Total Runs">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="target_hits">Target Hits</label>
                <input type="text" class="form-control" name="target_hits" id="target_hits" placeholder="Target Hits">
            </div>
        </div>
    </div>

    <div class="col-sm-12" style="text-align: right;">
        <a href="/management" class="btn btn-danger" style="width: 200px">Cancel</a>
        <button type="submit" class="btn btn-success" style="width: 200px">Save</button>
    </div>

</div>