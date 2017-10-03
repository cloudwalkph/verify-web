<div class="modal fade" id="manageTeam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width: 80%" role="document">
        <div class="modal-content">
            <form action="/management/projects/update/{{ $location->project_id }}/locations/{{ $location->id }}/update-team">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Manage Team</h4>
                </div>
                <div class="modal-body">
                    <input type="text" id="bas" name="bas" class="form-control" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>