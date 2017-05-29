@extends('layouts.app')

@section('scripts')
    <script>
        (function() {
            let $locations = $('.locations');

            $locations.on('click', '.add-location', function() {
                let $parent = $(this).parent()
                    .parent().parent()
                    .find('.location-item:last').clone();

                $(this).removeClass('add-location')
                    .removeClass('btn-primary')
                    .addClass('btn-danger')
                    .addClass('remove-location')
                    .html('Remove Location');

                $locations.append($parent);

                $parent = null;
            });

            $locations.on('click', '.remove-location', function() {
                $(this).parent()
                    .parent().remove();
            });
        })();
    </script>
@endsection

@section('content')

    <div class="info-section">
        <div class="info-title">
            <a href="/management" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
            <h2 style="color: #fff">
                Add New Project
            </h2>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="content">
                            <div class="row">
                                <div class="col-md-12">
                                    <h1>Project Details</h1>
                                </div>
                            </div>

                           <form action="/management/projects/create" method="POST">
                                {{ csrf_field() }}
                                @include('management.projects.form')
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection