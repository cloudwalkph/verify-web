@extends('layouts.app')

@section('styles')
    <style>
        .black-description ul li {
            list-style: none;
            width: 200px;
            text-align: center;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(function() {
            $('.locations-table').DataTable();
        });
    </script>
@endsection

@section('content')
    <div class="info-section">
        <div class="info-title">
            <div class="col-md-5 col-sm-6" style="display: inline-flex;">
                <a href="/home" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
                <h1 style="color: #fff">
                    {{ $project->name }}</h1>
            </div>
            <div class="col-sm-3">
                <h5 style="color: #B4B4B4;"><b>Runs Completed:</b></h5>
                <h5 class="text-primary">{{ $completed }} / {{ count($project['locations']) }} ({{ number_format(($completed / count($project['locations'])) * 100, 2) }}%)</h5>
            </div>
            <div class="col-sm-3">
                <h5 style="color: #B4B4B4;"><b>Reported Hits:</b></h5>
                <h5 class="text-primary">{{ $reported }}</h5>
            </div>
        </div>
    </div>

    <div class="black-description">
        <ul class="nav nav-pills">
            <li role="presentation"><a href="/projects/{{ $project->id }}">Overview</a></li>
            <li role="presentation" class="active"><a href="/projects/{{ $project->id }}/locations">Locations</a></li>
        </ul>
    </div>

    <div class="container-fluid" style="margin-top: -130px">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <h1>Locations</h1>
                        <hr>

                        <table class="table table-hover locations-table">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Project Type</th>
                                <th>Location Name</th>
                                <th>Date</th>
                                <th>Reported Hits</th>
                                <th>Audited Hits</th>
                                <th>Target Hits</th>
                                <th>Status</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($locations as $location)
                                <tr class="clickable" data-uri="/projects/{{ $location['project_id'] }}/locations/{{ $location['id'] }}">
                                    <td>
                                        <strong>{{ $location['id'] }}</strong>
                                    </td>

                                    <td>
                                        <strong>{{ $location['project_type'] }}</strong>
                                    </td>

                                    <td>
                                        <strong>{{ $location['name'] }}</strong>
                                    </td>

                                    <td>
                                        {{ $location['date'] }}
                                    </td>

                                    <td>
                                        {{ $location['reported_hits'] }}
                                    </td>

                                    <td>
                                        {{ $location['audited_hits'] }} (<span class="text-primary">{{ number_format($location['audit_percent'], 2) }}%</span>)
                                    </td>

                                    <td>
                                        {{ $location['target_hits'] }}
                                    </td>

                                    <td>
                                        {{ ucwords($location['status']) }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
