@extends('layouts.app')

@section('styles')
    <style>
        ul li {
            list-style: none;
        }
        .audit-side-panel {
            height: 110vh;
        }
        @media (min-width: 769px) and (max-width: 992px) {
            .hits-image-container {
                line-height: 100px;
            }
            .hits-image {
                height: 70px;
                width: 70px;
            }
        }
        @media (max-width: 768px) {
            .hits-value-container {
                text-align: center;
            }
            .audit-side-panel {
                height: auto;
                padding-bottom: 20px;
            }
            .image-container img {
                width: 100%;
            }
        }
    </style>
@endsection

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
            <div class="col-md-3 col-sm-4">
                <div class="panel panel-default audit-side-panel">
                    <div class="panel-body">
                        <div class="content">
                            <h3 style="margin: 30px 0;">Audit Report</h3>
                            <p style="margin-bottom: 30px; line-height: 30px">
                                Shows all the complete data and information gathered and recorded during the running of this event or project.
                            </p>
                            <button type="button" class="btn btn-primary" style="margin-bottom: 20px">Print Report</button>
                            <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/event-reports"
                               class="btn btn-primary">Event Report</a> <br>
                            <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/gps-reports"
                               class="btn btn-primary">GPS Report</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-sm-8">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="content">
                            <div class="image-container">
                                <img src="{{ asset('images/verify_white.png') }}" alt="logo" style="height: 100px">
                            </div>
                            <div class="row">

                                <div class="col-md-12">
                                    <h1>Audit Report</h1>
                                    <p>Shows data and info from each individual hit.</p>
                                </div>
                            </div>

                            <div class="row">
                               @foreach($hits as $hit)
                                <div class="col-md-4 col-sm-6" style="margin: 50px 0">
                                    <div class="col-md-6 col-sm-4 text-center hits-image-container">
                                        <img src="{{ $hit->image ? Storage::drive('s3')->url($hit->image) : '/images/placeholder.png' }}" height="120" width="120" class="img-circle hits-image" alt="">
                                    </div>
                                    <div class="col-md-6 col-sm-8 hits-value-container">
                                        <h3>{{ $hit->name }}</h3>
                                        <ul>
                                            {{ ($hit->email) ? '<li>'.$hit->email.'</li>' : '' }}
                                            {{ ($hit->contact_number) ? '<li>'.$hit->contact_number.'</li>' : '' }}
                                            @foreach($hit['answers'] as $answer)
                                                <li>{{ $answer->value }}</li>
                                            @endforeach
                                            <li>{{ $hit->created_at->toFormattedDateString() }}</li>
                                        </ul>


                                    </div>
                                </div>
                               @endforeach
                            </div>

                            <div class="text-center">
                                {{ $hits->links() }}
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