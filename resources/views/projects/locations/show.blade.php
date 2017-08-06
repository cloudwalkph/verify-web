@extends('layouts.app')

@section('content')
    <div class="info-section">
        <div class="info-title">
            <div class="col-sm-6" style="display: inline-flex;">
                <a href="/projects/{{ $project->id }}" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
                <h1 style="color: #fff">
                    {{ $project->name }}
                    <p class="info-sub-title">{{ $location->name }}</p>
                </h1>
            </div>
            <div class="col-sm-3">
                <h5 style="color: #B4B4B4;"><b>Reported Hits:</b></h5>
                <h5 class="text-primary">{{ $location->manual_hits }} / {{ $location->target_hits ? $location->target_hits : 'NA' }} </h5>
            </div>
        </div>

        <div class="info-body">
            <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/event-reports"
               class="btn btn-primary">Verify Audit Report</a>
        </div>
    </div>

    @component('components.location-description',
        ['project' => $project, 'location' => $location])
    @endcomponent

    <div class="container-fluid" style="margin-top: -130px">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">

                        @component('components.chart', ['project' => $project, 'location' => $location])
                            @slot('nav')
                                <li class="{{ $services && in_array('manual', $services) ? '' : 'hide' }} active">
                                    <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}">Manual</a>
                                </li>

                                <li class="{{ $services && in_array('automatic', $services) ? '' : 'hide' }}">
                                    <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/automated">Automated</a>
                                </li>

                                <li class="{{ $services && in_array('gps', $services) ? '' : 'hide' }}">
                                    <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/gps">GPS</a>
                                </li>

                                <li class="{{ count($videos) <= 0 ? 'hide' : '' }}"><a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/videos">Video</a></li>
                            @endslot
                            @slot('title')
                            @endslot
                            @slot('ongoingReport')
                            @endslot
                        @endcomponent

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
