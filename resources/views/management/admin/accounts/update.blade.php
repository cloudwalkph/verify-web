@extends('layouts.app') 

@section('content')

    <div class="info-section">
        <div class="info-title">
            <a href="/management/users" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
            <h2 style="color: #fff">
                Edit Account
            </h2>
        </div>

        <div class="info-body">
            <button class="btn btn-primary" data-target="#importModal" data-toggle="modal">Upload GPS Data</button>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="active tab-pane" id="profile">
                            <div class="content">

                                <form action="/management/users/update/{{$user->id}}" method="POST">
                                    {{ csrf_field() }}
                                    @include('management.admin.accounts.form')
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <p>IMPORT GPS DATA</p>
                </div>
                <form action="/management/users/update/{{$user->id}}/import-gps" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" id="userId" value="{{ $user->id }}">
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
@endsection

