@extends('layouts.management')

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#projects').DataTable();
        } );
    </script>
@endsection

@section('content')
    <div class="alert alert-primary" style="text-align: center">
        <img src="{{ asset('images/ic_sms_failed_24px.png') }}" alt="info"> CLICK ON A PROJECT TO VIEW MORE DETAILS
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h1 class="agency-title">Activations Advertising Inc</h1>
                                <h4>Monitor all the data from consumer engagement activities</h4>
                            </div>

                            <div class="col-md-6">
                                <a href="/management/projects/create"
                                   style="margin-top: 30px;"
                                   class="btn btn-primary pull-right">
                                    <i class="glyphicon glyphicon-plus"></i> Add New Project</a>
                            </div>
                        </div>


                        <hr>

                        <div class="col-md-12">
                            @include('components.success')
                        </div>

                        <table id="projects" class="table table-hover">
                            <thead>
                            <tr>
                                <th>Project Name</th>
                                <th>Active Runs</th>
                                <th>Completed Runs</th>
                                <th>Reported Hits</th>
                                <th>Audited Hits</th>
                                <th>Status</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($projects as $project)
                                <tr class="clickable" data-uri="/management/projects/update/{{ $project['id'] }}">
                                    <td>
                                        <strong>{{ $project['name'] }}</strong>
                                    </td>

                                    <td>
                                        {{ $project['active_runs'] }} / {{ count($project['locations']) }}
                                    </td>

                                    <td>
                                        {{ $project['completed_runs'] }} / {{ count($project['locations']) }}
                                    </td>

                                    <td>
                                        {{ $project['reported_hits']  }}
                                    </td>

                                    <td>
                                        {{ $project['audited_hits'] }} (<span class="text-primary">{{ number_format($project['audit_percent'], 2) }}%</span>)
                                    </td>

                                    <td>
                                        {{ ucwords($project['status']) }}
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