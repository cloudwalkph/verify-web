<div class="modal fade" id="manageTeam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width: 80%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Manage Team</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($location->users as $user)
                        <tr>
                            <td>{{ $user->profile->full_name }}</td>
                            <td>{{ $user->userGroup->name }}</td>
                            <td>
                                <button class="btn btn-danger">X</button>
                                <button class="btn btn-primary btn-manage-team" data-target="#importModal"
                                        data-user="{{ $user->id }}"
                                        data-toggle="modal">Import GPS</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>