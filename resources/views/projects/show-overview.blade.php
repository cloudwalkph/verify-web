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

@section('content')
    <div class="info-section">
        <div class="info-title">
            <div class="col-md-5 col-sm-6" style="display: inline-flex;">
                <a href="/home" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
                <h1 style="color: #fff">
                    {{ $project->name }}</h1>
            </div>
        </div>
    </div>

    <div class="black-description">
        <ul class="nav nav-pills">
            <li role="presentation"><a href="/projects/{{ $project->id }}">Locations</a></li>
            <li role="presentation" class="active"><a href="/projects/{{ $project->id }}/overview">Overall Report</a></li>
        </ul>
    </div>

    <div class="container-fluid" style="margin-top: -130px">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @component('components.chart', ['project' => $project, 'location' => []])
                            @slot('nav')
                            @endslot

                            @slot('title')
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h3>Analytics</h3>
                                        <p>Real time Data from <strong>{{ $project->name }}</strong> activities.</p>
                                    </div>

                                    <div class="col-sm-3">
                                        <h5 style="color: #585858; margin-top: 15px; font-size: 20px;"><b>Runs Completed / Total Runs</b></h5>
                                        <h5 class="text-primary" style="font-size: 18px;">{{ $completed }} / {{ count($project['locations']) }} ({{ number_format(($completed / count($project['locations'])) * 100, 2) }}%)</h5>
                                    </div>
                                    <div class="col-sm-3">
                                        <h5 style="color: #585858; margin-top: 15px; font-size: 20px;"><b>Reported Hits / Target Hits</b></h5>
                                        <h5 class="text-primary" style="font-size: 18px;">{{ $reported }} / {{ $target ? $target : 'NA' }} ({{ $target ? number_format(($reported / $target) * 100, 2) : '0' }}%)</h5>
                                    </div>
                                </div>
                            @endslot

                            @slot('ongoingReport')
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Achieved</th>
                                        <th>Target</th>
                                        <th>Percentage</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th>Number of Runs</th>
                                        <td>{{ $completed }}</td>
                                        <td>{{ count($project['locations']) }}</td>
                                        <td>{{ number_format(($completed / count($project['locations'])) * 100, 2) }}%</td>
                                    </tr>
                                    <tr>
                                        <th>Hits</th>
                                        <td>{{ $reported }}</td>
                                        <td>{{ $target ? $target : 'NA' }}</td>
                                        <td>{{ $target ? number_format(($reported / $target) * 100, 2) : '0' }}%</td>
                                    </tr>
                                    </tbody>
                                </table>
                            @endslot
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="projectId" value="{{ $project->id }}">
@endsection
