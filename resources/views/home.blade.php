@extends('layouts.app')

@section('content')
<div class="alert alert-primary" style="text-align: center">
    <img src="{{ asset('images/ic_sms_failed_24px.png') }}" alt="info"> CLICK ON A PROJECT TO VIEW MORE DETAILS
</div>

<div class="white-description">
    <h1>DASHBOARD</h1>
    <h5>Monitor all the data from consumer engagements activities. </h5>
</div>

<div class="container-fluid" style="margin-top: -55px;">

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">

                    <legend>My Projects</legend>

                    @component('components.projects', ['projects' => $projects])
                    @endcomponent
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

                    <legend>
                        Shared Projects
                        <p class="help-block" style="font-size: 14px">
                            These are projects shared with you by the owner that allows you to view
                            all data and information from the shared project.
                        </p>
                    </legend>

                    <table class="table table-hover projects-table">
                        <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Active Runs</th>
                            <th>Completed Runs</th>
                            <th>Reported Hits</th>
                            <th>Verified Hits</th>
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
