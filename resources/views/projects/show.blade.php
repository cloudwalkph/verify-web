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
            <li role="presentation" class="active"><a href="/projects/{{ $project->id }}/locations">Locations</a></li>
            <li role="presentation"><a href="/projects/{{ $project->id }}/overview">Overall Report</a></li>
        </ul>
    </div>

    <div class="container-fluid" style="margin-top: -130px">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-sm-4">
                                <h1>Locations</h1>
                            </div>

                            <div class="col-sm-2 text-center">
                                <h5 style="color: #585858; margin-top: 15px; font-size: 20px;"><b>Runs Completed</b></h5>
                                <h5 class="text-primary" style="font-size: 18px;">{{ number_format($completed, 0, '.', ',') }}</h5>
                            </div>

                            <div class="col-sm-2 text-center">
                                <h5 style="color: #585858; margin-top: 15px; font-size: 20px;"><b>Total Runs</b></h5>
                                <h5 class="text-primary" style="font-size: 18px;">{{ $project['target_runs'] ? number_format($project['target_runs'], 0, '.', ',') : 'NA' }} ({{ $project['target_runs'] ? number_format(($completed / $project['target_runs']) * 100, 2, '.', ',') : 'NA' }}%)</h5>
                            </div>

                            <div class="col-sm-2 text-center">
                                <h5 style="color: #585858; margin-top: 15px; font-size: 20px;"><b>Reported Hits</b></h5>
                                <h5 class="text-primary" style="font-size: 18px;">{{ number_format($reported, 0, '.', ',') }}</h5>
                            </div>

                            <div class="col-sm-2 text-center">
                                <h5 style="color: #585858; margin-top: 15px; font-size: 20px;"><b>Target Hits</b></h5>
                                <h5 class="text-primary" style="font-size: 18px;">{{ $target ? number_format($target, 0, '.', ',') : 'NA' }} ({{ $target ? number_format(($reported / $target) * 100, 2, '.', ',') : '0' }}%)</h5>
                            </div>
                        </div>

                        <hr>

                        @component('components.project-locations', ['locations' => $locations])
                        @endcomponent

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
