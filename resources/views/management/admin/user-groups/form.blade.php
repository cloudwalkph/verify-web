<div class="row">
    <div class="col-md-12">
        @include('components.errors')
        @include('components.success')
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name" 
                value='{{ isset( $user_group->name ) ? $user_group->name : "" }}' placeholder="Name">
        </div>
    </div>

    <div class="col-sm-12" style="text-align: right;">
        <a href="/management/profile" class="btn btn-danger" style="width: 200px">Cancel</a>
        <button type="submit" class="btn btn-success" style="width: 200px">Save</button>
    </div>

</div>