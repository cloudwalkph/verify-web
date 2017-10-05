<div class="modal fade" id="addClientToProject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <p>Add Client</p>
            </div>
            <form action="/management/projects/update/{{$project->id}}/client" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    {{ csrf_field() }}


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="user_id">Client Name</label>
                                <select class="form-control" id="client_id" name="user_id"></select>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Add Client</button>
                </div>
            </form>
        </div>
    </div>
</div>