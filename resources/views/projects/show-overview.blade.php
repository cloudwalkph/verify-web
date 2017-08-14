@extends('layouts.app')

@section('styles')
    <style>
        .black-description ul li {
            list-style: none;
            width: 200px;
            text-align: center;
        }
        .overall {
            margin-top: -130px;
        }
        .summary-title {
            color: #585858;
            margin-top: 15px;
            font-size: 20px;
        }
        .overall .image-container {
            display: none;
        }
        @media print {
            .info-section, .black-description {
                display: none;
            }
            .overall {
                margin-top: 0;
            }
            .summary-title {
                font-size: 14px;
            }
            .print-btn {
                display: none;
            }
            .graph-description-container {
                padding-left: 10px;
            }
            .overall .image-container {
                display: block;
            }
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

    <div class="container-fluid overall">
        <div class="image-container" style="margin-top: 50px">
            <img src="{{ asset('images/verify_white.png') }}" alt="logo" style="height: 100px">
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @component('components.chart', ['project' => $project, 'location' => []])
                            @slot('nav')
                            @endslot
                            @slot('timeandvideo')
                                <div class="time-and-video">
                                    <div class="col-md-12">
                                        <div class="graph-description-container">
                                            <h2>Timestamp</h2>
                                            <p class="help-block">Data or hits recorded during specific hours of the day or run.</p>
                                        </div>
                                        <div id="time-graph"></div>
                                    </div>
                                </div>
                            @endslot

                            @slot('title')
                                <div class="row">
                                    <div class="col-sm-4">
                                        <h3>Overall Report</h3>
                                        <p>{{ \Carbon\Carbon::today('asia/manila')->toFormattedDateString() }}</p>
                                    </div>

                                </div>
                            @endslot

                            @slot('ongoingReport')
                                <div class="col-md-12 text-right" style="margin: 10px 0">
                                    <a href="javascript:window.print()" class="btn btn-primary print-btn" > <i class="fa fa-print fa-lg"></i> Print Report</a>
                                </div>


                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th colspan="4">
                                            <h1>Overall Targets</h1>
                                        </th>
                                    </tr>
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
                                            <td>{{ number_format($completed, 0, '.', ',') }}</td>
                                            <td>{{ $project['target_runs'] ? number_format($project['target_runs'], 0, '.', ',') : 'NA' }}</td>
                                            <td>{{ $project['target_runs'] ? number_format(($completed / $project['target_runs']) * 100, 2, '.', ',') : 'NA' }}%</td>
                                        </tr>
                                        <tr>
                                            <th>Number of Hits</th>
                                            <td>{{ number_format(101313, 0, '.', ',') }}</td>
                                            <td>{{ number_format(276000, 0, '.', ',') }}</td>
                                            <td>{{ number_format((101313 / 276000) * 100, 2, '.', ',') }}%</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th colspan="4">
                                            <h1>On-Going Runs</h1>
                                            <small>This are the currenty on-going runs</small>
                                        </th>
                                    </tr>
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
                                        <td>{{ number_format($completed, 0, '.', ',') }}</td>
                                        <td>{{ number_format(count($project['locations']), 0, '.', ',') }}</td>
                                        <td>{{ number_format(($completed / count($project['locations'])) * 100, 2, '.', ',') }}%</td>
                                    </tr>
                                    <tr>
                                        <th>Hits</th>
                                        <td>{{ number_format($reported, 0, '.', ',') }}</td>
                                        <td>{{ $target ? number_format($target, 0, '.', ',') : 'NA' }}</td>
                                        <td>{{ $target ? number_format(($reported / $target) * 100, 2, '.', ',') : '0' }}%</td>
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
