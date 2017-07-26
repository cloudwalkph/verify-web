@extends('layouts.management')

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
            <a href="/management/projects" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
            <h2 style="color: #fff">
                Edit Project - {{ strtoupper($project->name) }}
            </h2>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="content">
                            <div class="row">
                                <div class="col-md-12">
                                    <h1>Project Details</h1>
                                </div>
                            </div>

                            <hr>

                           <form action="/management/projects/update/{{$project->id}}" method="POST">
                                {{ csrf_field() }}
                               <div class="row">
                                   <div class="col-md-12">
                                       @include('components.errors')
                                   </div>

                                   <div class="col-md-12">
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label for="name">Project Name</label>
                                               <input type="text" class="form-control" name="name" id="name" placeholder="Project Name"
                                                      value='{{ isset( $project->name ) ? $project->name : old('name') }}'>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label for="user_id">Client Name</label>
                                               <select class="form-control" id="user_id" name="user_id">
                                                   @foreach($clients as $client)
                                                       <option value="{{ $client->id }}" {{ old('user_id') == $client->id ? 'selected' : '' }}>
                                                           {{ $client->profile->full_name }}
                                                       </option>
                                                   @endforeach
                                               </select>
                                           </div>
                                       </div>

                                       <div class="col-md-12">
                                           <div class="form-group">
                                               <label for="status">Status</label>
                                               <select class="form-control" id="status" name="status">
                                                   <option value="pending" {{ $project->status === 'pending' ? 'selected' : '' }}>
                                                       Pending
                                                   </option>

                                                   <option value="active" {{ $project->status === 'active' ? 'selected' : '' }}>
                                                       Active
                                                   </option>

                                                   <option value="cancelled" {{ $project->status === 'cancelled' ? 'selected' : '' }}>
                                                       Cancelled
                                                   </option>
                                               </select>
                                           </div>
                                       </div>
                                   </div>

                                   <div class="col-sm-12" style="text-align: right;">
                                       <a href="/management" class="btn btn-danger" style="width: 200px">Cancel</a>
                                       <button type="submit" class="btn btn-primary" style="width: 200px">Save</button>
                                   </div>

                               </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="content">
                            <div class="row">
                                <div class="col-md-6">
                                    <h1>Project Locations</h1>
                                </div>

                                <div class="col-md-6">
                                    <button class="btn btn-primary pull-right" style="margin-top: 30px;">
                                        <i class="glyphicon glyphicon-plus"></i> Add Location</button>
                                </div>
                            </div>

                            <hr>

                            <table class="table table-bordered locations-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Location Name</th>
                                        <th>Services</th>
                                        <th>Vbox Channel</th>
                                        <th>Reported Hits</th>
                                        <th>Audited Hits</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($locations as $location)
                                        <tr>
                                            <td>{{ $location['date'] }}</td>
                                            <td>{{ $location['name'] }}</td>
                                            <td>{{ $location['services'] }}</td>
                                            <td>{{ $location['vboxes'] }}</td>
                                            <td>{{ $location['reported_hits'] }}</td>
                                            <td>{{ $location['audited_hits'] }} (<span class="text-primary">{{ $location['audit_percent'] }}</span>%)</td>
                                            <td>{{ $location['status'] }}</td>
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
@endsection