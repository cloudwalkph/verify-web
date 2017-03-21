@extends('layouts.app')

@section('content')
    <div class="info-section">
        <div class="info-title">
            <h1 style="color: #fff"><a href="/home"><i class="glyphicon glyphicon-chevron-left"></i></a> {{ $project->name }}</h1>
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

                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Project Name</th>
                                <th>Date</th>
                                <th>Achieved Target Hits</th>
                                <th>Status</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($locations as $location)
                                <tr class="clickable" data-uri="/locations/{{ $location->id }}">
                                    <td>
                                        <strong>{{ $location->name }}</strong>
                                    </td>

                                    <td>
                                        {{ $location->date }}
                                    </td>

                                    <td>
                                        {{ $location->hits()->count() }} / {{ $location->target_hits }}
                                    </td>

                                    <td>
                                        {{ ucwords($location->status) }}
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
