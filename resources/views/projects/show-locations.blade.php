@extends('layouts.app')

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
            <a href="/home" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
            <h1 style="color: #fff">{{ $project->name }}</h1>
        </div>

        <div class="info-body">
            <button class="btn btn-primary">Verify Audit Report</button>
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
                        <div class="col-md-4 col-sm-8 col-xs-12">
                            <div class="alert alert-primary" style="margin: 0;box-shadow: 1px 3px 5px #FF7300;">
                                <img src="{{ asset('images/ic_sms_failed_24px.png') }}" alt="info"> &nbsp; CLICK ON A PROJECT TO VIEW MORE DETAILS
                            </div>
                        </div>

                        <div class="col-md-12 col-xs-12">
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
    </div>
@endsection
