@extends('layouts.app')

@section('content')
    <div class="alert alert-primary" style="text-align: center">
        <img src="{{ asset('images/ic_sms_failed_24px.png') }}" alt="info"> CLICK ON A PROJECT TO VIEW MORE DETAILS
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1 class="agency-title">Activations Advertising Inc</h1>
                        <h4>Monitor all the data from consumer engagement activities</h4>

                        <a href="" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Add New Project</a>
                        <hr>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Project Name</th>
                                <th>Active Runs</th>
                                <th>Achieve Sampling Target Hits</th>
                                <th>Completed Runs</th>
                                <th>Status</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($projects as $project)
                                <tr class="clickable" data-uri="/projects/{{ $project->id }}">
                                    <td>
                                        <strong>{{ $project->name }}</strong>
                                    </td>

                                    <td>
                                        {{ $project->locations()->onGoing()->count() }} / {{ $project->locations()->total() }}
                                    </td>

                                    <td>
                                        {{ get_total_hits_for_project($project->locations) > $project->locations()->sum('target_hits') ? $project->locations()->sum('target_hits') : get_total_hits_for_project($project->locations) }} / {{ $project->locations()->sum('target_hits') }}
                                    </td>

                                    <td>
                                        {{ $project->locations()->completed()->count() }} / {{ $project->locations()->total() }}
                                    </td>

                                    <td>
                                        {{ ucwords($project->status) }}
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