@extends('layouts.management')

@section('scripts')
    <script>
        $(function() {
            $('.locations-table').DataTable();

            // clients selectize
            $('#user_id').selectize({
                placeholder: 'Select a client user',
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                create: false,
                render: {
                    option: function(item, escape) {
                        return '<div>' +
                            '<span class="title">' +
                            '<span>' + escape(item.name) + '</span>' +
                            '</span>' +
                            '</div>';
                    }
                },
                load: function(query, callback) {
                    if (!query.length) return callback();

                    let url = '/management/users/json?group=2&q=' + encodeURIComponent(query);

                    axios.get(url).then((response) => {
                        callback(response.data);
                    }).catch((err) => {
                        callback();
                    });

                }
            });

            // brand ambassadors selectize
            $('#bas').selectize({
                placeholder: 'Select brand ambassadors',
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                plugins: ['remove_button'],
                create: false,
                render: {
                    option: function(item, escape) {
                        return '<div>' +
                            '<span class="title">' +
                            '<span>' + escape(item.name) + '</span>' +
                            '</span>' +
                            '</div>';
                    }
                },
                load: function(query, callback) {
                    if (!query.length) return callback();

                    let url = '/management/users/json?group=3&q=' + encodeURIComponent(query);

                    axios.get(url).then((response) => {
                        callback(response.data);
                    }).catch((err) => {
                        callback();
                    });

                }
            });

            // Add Multiple videos
            $(document).on('click', '.add-video', function() {
                let $el = $(this).parent()
                    .parent()
                    .parent()
                    .clone();

                $el.find('.input-field').val('');

                let $parent = $(this).parent()
                    .parent()
                    .parent()
                    .parent();

                $parent.append($el);

                $(this).html('<i class="glyphicon glyphicon-remove"></i>')
                    .removeClass('btn-default')
                    .addClass('btn-danger');
            });
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
                                                   <option value="{{ $client['id'] }}" selected>
                                                       {{ $client['name'] }}
                                                   </option>
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
                                    <button class="btn btn-primary pull-right"
                                            type="button"
                                            data-target="#addLocation"
                                            data-toggle="modal"
                                            style="margin-top: 30px;">
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
                                        <th>Video</th>
                                        <th>Reported Hits</th>
                                        <th>Audited Hits</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($locations as $location)
                                        <tr class="clickable" data-uri="/management/projects/update/{{ $project['id'] }}/locations/{{ $location['id'] }}">
                                            <td>{{ $location['date'] }}</td>
                                            <td style="max-width: 250px">{{ $location['name'] }}</td>
                                            <td>{{ $location['services'] }}</td>
                                            <td>{{ $location['vboxes'] }}</td>
                                            <td>{{ $location['reported_hits'] }}</td>
                                            <td>{{ $location['audited_hits'] }} (<span class="text-primary">{{ number_format($location['audit_percent'], 2) }}</span>%)</td>
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

    @include('management.projects.add-location')
@endsection