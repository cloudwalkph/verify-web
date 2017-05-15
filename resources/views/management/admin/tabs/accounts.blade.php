<div class="tab-pane" id="accounts">
    <div class="content">
        <div class="row">
            <div class="col-md-6">
                <h1>Accounts</h1>
            </div>
            <div class="col-md-6">
                <a href="/management/accounts" class="btn btn-primary pull-right" style="width: 200px; margin-top: 25px">Create New Account</a>
            </div>
        </div>

        <table id="accounts" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>User Group</th>
                <th>Email Address</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
                @foreach($accounts as $account)
                    <tr class="clickable" data-uri="/management/accounts/update/{{ $account->id }}">
                        <td>{{ $account->profile->first_name }}</td>
                        <td>{{ $account->profile->last_name }}</td>
                        <td>{{ $account->userGroup->name }}</td>
                        <td>{{ $account->email }}</td>
                        <td>{{ $account->created_at->toFormattedDateString() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
</div>