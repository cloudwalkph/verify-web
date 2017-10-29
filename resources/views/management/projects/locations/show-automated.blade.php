@extends('layouts.management')

@section('scripts')
    <script>
        $(function() {
            $('.locations-table').DataTable()
        })
    </script>
@endsection

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
                        <li class="{{ $services && in_array('manual', $services) ? '' : 'hide' }}">
                            <a href="/management/projects/update/{{ $project->id }}/locations/{{ $location->id }}">Manual</a>
                        </li>

                        <li class="{{ $services && in_array('automatic', $services) ? '' : 'hide' }} active">
                            <a href="/management/projects/update/{{ $project->id }}/locations/{{ $location->id }}/automated">Automated</a>
                        </li>

                        <li class="{{ $services && in_array('gps', $services) ? '' : 'hide' }}">
                            <a href="/management/projects/update/{{ $project->id }}/locations/{{ $location->id }}/gps">GPS</a>
                        </li>

                        <li class="{{ count($videos) <= 0 ? 'hide' : '' }}">
                            <a href="/management/projects/update/{{ $project['id'] }}/locations/{{ $location['id'] }}/videos">Video
                            </a>
                        </li>

                        @endslot

                        @slot('title')
                        @endslot
                        @slot('ongoingReport')
                        @endslot
                        @slot('timeandvideo')
                        @endslot
                        @endcomponent

                        <table class="table table-bordered locations-table">
                            <thead>
                            <tr>
                                <th>File</th>
                                <th>Processing Time</th>
                                <th>Completed Time</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($raws as $raw)
                                <tr>
                                    <td>{{ $raw->file }}</td>
                                    <td>{{ $raw->processing_time }}</td>
                                    <td>{{ $raw->completed_time }}</td>
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