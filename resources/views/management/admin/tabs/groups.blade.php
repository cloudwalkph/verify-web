<div class="tab-pane" id="userGroups">
    <div class="content">
        <div class="row">
            <div class="col-md-6">
                <h1>User Group</h1>
            </div>
            <div class="col-md-6">
                <a href="/management/user-groups" class="btn btn-primary pull-right" style="width: 200px; margin-top: 25px">Create New User Group</a>
            </div>
        </div>

        <table id="user-groups" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th style="display: none;">Id</th>
                <th>Name</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
                @foreach($user_groups as $group)
                    <tr class="clickable" data-uri="/management/user-groups/update/{{ $group->id }}">
                        <td>{{ $group->name }}</td>
                        <td>{{ $group->created_at->toFormattedDateString() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
</div>