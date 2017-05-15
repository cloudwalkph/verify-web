@extends('layouts.app') 

@section('content')

    <div class="info-section">
        <div class="info-title">
            <a href="/management/profile" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
            <h2 style="color: #fff">
                Edit Brand
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

                                <form action="/management/brands/update/{{$brand->id}}" method="POST">
                                    {{ csrf_field() }}
                                    @include('management.admin.brands.form')

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

