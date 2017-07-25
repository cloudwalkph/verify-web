@extends('layouts.management') 

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#users').DataTable();
        } );
    </script>
@endsection

@section('content')

    <div class="info-section">
        <div class="info-title">
            <a href="/management/profile" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
            <h2 style="color: #fff">
                Users
            </h2>
        </div>
    </div>
    

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="active tab-pane" id="profile">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h1>Users</h1>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="/management/users/create" class="btn btn-primary pull-right" style="width: 200px; margin-top: 25px">Create New Account</a>
                                    </div>
                                </div>

                                <hr>

                                 <table id="users" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>User Group</th>
                                        <th>Email Address</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                            <tr class="clickable" data-uri="/management/users/update/{{ $user->id }}">
                                                <td>{{ $user->profile->first_name }} {{ $user->profile->last_name }}</td>
                                                <td>{{ $user->userGroup->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->created_at->toFormattedDateString() }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

