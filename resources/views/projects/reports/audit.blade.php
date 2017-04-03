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
                <div class="panel panel-default" style="min-height: 100vh;">
                    <div class="panel-body">
                        <div class="content">
                            <h3 style="margin: 30px 0;">Audit Report</h3>
                            <p style="margin-bottom: 30px; line-height: 30px">
                                Shows all the complete data and information gathered and recorded during the running of this event or project.
                            </p>
                            <button type="button" class="btn btn-primary" style="margin-bottom: 20px" onclick="frames['frameAudit'].print()">Print Report</button>
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

                                <div class="col-md-12">
                                    <h1>Audit Report</h1>
                                    <p>Shows data and info from each individual hit.</p>
                                </div>
                            </div>

                            <div class="row">
                               @foreach($hits as $hit)
                                <div class="col-md-6" style="margin: 50px 0">
                                    <div class="col-md-6 text-center">
                                        <img src="{{ \Storage::url($hit->image) }}" height="150" width="150" class="img-circle" alt="">
                                    </div>
                                    <div class="col-md-6">
                                        <h3>{{ $hit->name }}</h3>
                                        <p>{{ $hit->email }}</p>
                                        <p>{{ $hit->contact_number }}</p>
                                        <p>{{ $hit->created_at->toFormattedDateString() }}</p>
                                    </div>
                                </div>
                               @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <iframe src="/projects/{{ $project->id }}/locations/{{ $location->id }}/audit-reports/preview" name="frameAudit" style="width: 0; height: 0"></iframe>
@endsection
