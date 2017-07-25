@extends('layouts.app')

@section('scripts')
    <script>
        $(function() {
            $('.projects-table').DataTable();
        });
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

                    <legend>My Projects</legend>
                    <table class="table table-hover projects-table">
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
                                <tr class="clickable" data-uri="/projects/{{ $project['id'] }}">
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
                                        {{ $project['audited_hits'] }} ({{ number_format($project['audit_percent'], 2) }}%)
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

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">

                    <legend>Shared Projects</legend>
                    <table class="table table-hover projects-table">
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
                        @foreach ($sharedProjects as $sharedProject)
                            <tr class="clickable" data-uri="/projects/{{ $sharedProject->project->id }}">
                                <td>
                                    <strong>{{ $sharedProject->project->name }}</strong>
                                </td>

                                <td>
                                    {{ $sharedProject->project->locations()->onGoing()->count() }} / {{ $sharedProject->project->locations()->total() }}
                                </td>

                                <td>
                                    {{ $sharedProject->project->locations()->completed()->count() }} / {{ $sharedProject->project->locations()->total() }}
                                </td>

                                <td>
                                    Reported
                                </td>

                                <td>
                                    Audited Hits
                                </td>

                                <td>
                                    {{ ucwords($sharedProject->project->status) }}
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
