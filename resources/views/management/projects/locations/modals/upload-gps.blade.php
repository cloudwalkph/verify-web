<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <p>IMPORT GPS DATA</p>
            </div>
            <form action="/management/projects/update/{{ $project['id'] }}/locations/{{ $location['id'] }}/gps" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="user_id" id="userId">

                    <div class="row">
                        <div class="col-md-12">
                            <label for="file">Select excel file to import: </label>
                            <input type="file" class="form-control" name="gps_file">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success importBtn">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>