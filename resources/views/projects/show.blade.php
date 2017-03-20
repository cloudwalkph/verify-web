@extends('layouts.app')

@section('content')
    <div class="alert alert-primary" style="text-align: center">
        <img src="{{ asset('images/ic_sms_failed_24px.png') }}" alt="info"> CLICK ON A PROJECT TO VIEW MORE DETAILS
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
