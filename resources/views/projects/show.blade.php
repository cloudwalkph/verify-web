@extends('layouts.app')

@section('scripts')
    <script>
        $(function() {
            $('.locations-table').DataTable();
        });
    </script>
@endsection

@section('content')
    <div class="info-section">
        <div class="info-title">
            <a href="/home" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
            <h1 style="color: #fff">{{ $project->name }}</h1>
        </div>

        <div class="info-body">
            <button class="btn btn-primary">Verify Audit Report</button>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <h1>Locations</h1>
                        <hr>

                        <table class="table table-hover locations-table">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Location Name</th>
                                <th>Date</th>
                                <th>Reported Hits</th>
                                <th>Audited Hits</th>
                                <th>Status</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($locations as $location)
                                <tr class="clickable" data-uri="/projects/{{ $location['project_id'] }}/locations/{{ $location['id'] }}">
                                    <td>
                                        <strong>{{ $location['id'] }}</strong>
                                    </td>

                                    <td>
                                        <strong>{{ $location['name'] }}</strong>
                                    </td>

                                    <td>
                                        {{ $location['date'] }}
                                    </td>

                                    <td>
                                        {{ $location['reported_hits'] }}
                                    </td>

                                    <td>
                                        {{ $location['audited_hits'] }} (<span class="text-primary">{{ number_format($location['audit_percent'], 2) }}%</span>)
                                    </td>

                                    <td>
                                        {{ ucwords($location['status']) }}
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
