<div class="row">
    <div class="col-md-12">
        @include('components.errors')
        @include('components.success')
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="company_name">Company Name</label>
            <input type="text" class="form-control" name="company_name" id="company_name" 
                value='{{ isset( $brand->company_name ) ? $brand->company_name : "" }}' placeholder="Company Name">
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="brand">Brand</label>
            <input type="text" class="form-control" name="brand" id="brand" 
                value='{{ isset( $brand->brand ) ? $brand->brand : "" }}' placeholder="Brand">
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="marketing_manager">Marketing Manager</label>
            <input type="text" class="form-control" name="marketing_manager" id="marketing_manager" 
                value='{{ isset( $brand->marketing_manager ) ? $brand->marketing_manager : "" }}' placeholder="Marketing Manager">
        </div>
    </div>

    <div class="col-sm-12" style="text-align: right;">
        <a href="/management/profile" class="btn btn-danger" style="width: 200px">Cancel</a>
        <button type="submit" class="btn btn-success" style="width: 200px">Save</button>
    </div>

</div>