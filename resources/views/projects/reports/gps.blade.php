@extends('layouts.app')

@section('content')

    <div class="info-section">
        <div class="info-title">
            <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
            <h1 style="color: #fff">
                {{ $project->name }}
                <p class="info-sub-title">{{ $location->name }}</p>
            </h1>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-default" style="min-height: 900px;">
                    <div class="panel-body">
                        <div class="content">
                            <h3 style="margin: 30px 0;">GPS Report</h3>
                            <p style="margin-bottom: 30px; line-height: 30px">
                                Shows all the complete data and information gathered and recorded during the running of this event or project.
                            </p>
                            <button type="button" class="btn btn-primary" style="margin-bottom: 20px">Print Report</button>
                            <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/audit-reports"
                               class="btn btn-primary">Audit Report</a> <br>
                            <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/event-reports"
                               class="btn btn-primary">Event Report</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="content">
                            <div class="image-container">
                                <img src="{{ asset('images/verify_white.png') }}" alt="logo" style="height: 100px">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <h2>{{ $project->name }}</h2>
                                    <p>{{ $location->name }}</p>
                                </div>
                                <div class="col-md-2" style="margin-top: 15px">
                                    <h5>Status:</h5>
                                    <p class="text-primary">{{ ucwords($location->status) }}</p>
                                </div>
                                <div class="col-md-2" style="margin-top: 15px">
                                    <h5>Last Updated:</h5>
                                    <p class="text-primary">{{ count($location->hits) > 0 ? $location->hits[count($location->hits) - 1]->created_at->toFormattedDateString() : 'N/A' }}</p>
                                </div>

                                <div class="col-md-4" style="margin-top: 15px">
                                    <h5>Achieve Sampling Target Hits:</h5>
                                    <p class="text-primary">{{ $location->hits()->count() > $location->target_hits ? $location->target_hits : $location->hits()->count() }} / {{ $location->target_hits }}</p>
                                </div>
                                <div class="col-md-12"><hr></div>

                                <div class="col-md-12">
                                    <h2>ACTIVITY DETAIL REPORT:</h2>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr class="tableheader" style="background-color: #FF7300; color: white;">
                                            <th>Date</th>
                                            <th>User</th>
                                            <th>Date and Time</th>
                                            <th>Location</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($locations as $location)
                                            <tr>
                                                <td>{{ $location->created_at->toFormattedDateString() }}</td>
                                                <td>{{ $location->user->profile->full_name }}</td>
                                                <td>{{ $location->created_at }}</td>
                                                <td>{{ $location->formatted_address }}</td>
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
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            function openPrintWindow() {

                var printWindow = window.open(location.protocol + '//' + location.host + location.pathname+'/preview');

                var printAndClose = function () {
                    if (printWindow.document.readyState == 'complete') {
                        clearInterval(sched);
                        printWindow.print();
                        printWindow.close();
                    }
                }

                var sched = setInterval(printAndClose, 1000);
            }
            jQuery(document).ready(function ($) {
                $("button").on("click", function (e) {
                    e.preventDefault();
                    openPrintWindow();
                });
            });
        });
    </script>
@endsection
