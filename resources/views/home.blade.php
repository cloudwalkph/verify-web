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

                    <legend>My Projects</legend>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Project Name</th>
                                <th>Active Runs</th>
                                <th>Achieve Sampling Target Hits</th>
                                <th>Completed Runs</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($projects as $project)
                                <tr class="clickable" data-uri="/projects/{{ $project->id }}">
                                    <td>
                                        <strong>{{ $project->name }}</strong>
                                    </td>

                                    <td>
                                        {{ $project->locations()->onGoing()->count() }} / {{ $project->locations()->total() }}
                                    </td>

                                    <td>
                                        {{ get_total_hits_for_project($project->locations) > $project->locations()->sum('target_hits') ? $project->locations()->sum('target_hits') : get_total_hits_for_project($project->locations) }} / {{ $project->locations()->sum('target_hits') }}
                                    </td>

                                    <td>
                                        {{ $project->locations()->completed()->count() }} / {{ $project->locations()->total() }}
                                    </td>

                                    <td>
                                        {{ ucwords($project->status) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                    <legend>Shared Projects</legend>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Active Runs</th>
                            <th>Achieved Target Hits</th>
                            <th>Completed Runs</th>
                            <th>Status</th>
                        </tr>
                        </thead>

                        {{--<tbody>--}}
                        {{--@foreach ($sharedProjects as $sharedProject)--}}
                            {{--<tr class="clickable" data-uri="/projects/{{ $sharedProject->project->id }}">--}}
                                {{--<td>--}}
                                    {{--<strong>{{ $sharedProject->project->name }}</strong>--}}
                                {{--</td>--}}

                                {{--<td>--}}
                                    {{--{{ $sharedProject->project->locations()->onGoing()->count() }} / {{ $sharedProject->project->locations()->total() }}--}}
                                {{--</td>--}}

                                {{--<td>--}}
                                    {{--{{ get_total_hits_for_project($sharedProject->project->locations) }} / {{ $sharedProject->project->locations()->sum('target_hits') }}--}}
                                {{--</td>--}}

                                {{--<td>--}}
                                    {{--{{ $sharedProject->project->locations()->completed()->count() }} / {{ $sharedProject->project->locations()->total() }}--}}
                                {{--</td>--}}

                                {{--<td>--}}
                                    {{--{{ ucwords($sharedProject->project->status) }}--}}
                                {{--</td>--}}
                            {{--</tr>--}}
                        {{--@endforeach--}}
                        {{--</tbody>--}}
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
