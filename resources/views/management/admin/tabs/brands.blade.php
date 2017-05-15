<div class="tab-pane" id="brands">
    <div class="content">
        <div class="row">
            <div class="col-md-6">
                <h1>Brands</h1>
            </div>
            <div class="col-md-6">
                <a href="/management/brands" class="btn btn-primary pull-right" style="width: 200px; margin-top: 25px">Create New Brand</a>
            </div>
        </div>

        <table id="brands" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Company Name</th>
                <th>Brand</th>
                <th>Marketing Manager</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
                @foreach($brands as $brand)
                    <tr class="clickable" data-uri="/management/brands/update/{{ $brand->id }}">
                        <td>{{ $brand->company_name }}</td>
                        <td>{{ $brand->brand }}</td>
                        <td>{{ $brand->marketing_manager }}</td>
                        <td>{{ $brand->created_at->toFormattedDateString() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
</div>