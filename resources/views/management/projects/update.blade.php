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

    <script type="text/javascript">
        (function() {
            let answers = [];
            let hits = [];

            let height = $('.panel-body').css('height');
            $('.overlay').css('height', height);

            let projectId = $('#projectId').val();
            let url = `/projects/${projectId}/locations/get-hits`;
            let demographicsUrl = `/projects/${projectId}/locations/get-demographics`;

            let getHits = () => axios.get(url);
            let getDemographics = () => axios.get(demographicsUrl);

            axios.all([getHits(), getDemographics()]).then(
                axios.spread(function (hitsRes, answersRes) {
                    hits = hitsRes.data;
                    answers = answersRes.data;


                    console.log(answers);
                    drawCharts();

                    $('.overlay').addClass('hide');
                })).catch((error) => {
                    console.log(error);
                    $('.overlay').addClass('hide');
                });

            // Load the Visualization API and the corechart package.
            google.charts.load('current', {'packages':['corechart']});

            // Set a callback to run when the Google Visualization API is loaded.
            google.charts.setOnLoadCallback(drawCharts);

            function drawCharts() {
                recogDrawLineChart();
                recogDrawPieChart();
                recogDrawBarChart();
            }

            function createData(pollId, $tableHeader) {
                let arr = [];
                for (let answer of answers) {
                    if (answer.poll_id != pollId) {
                        continue;
                    }

                    arr.push([answer.value, 1]);
                }

                let dt = google.visualization.arrayToDataTable([
                    $tableHeader,
                    ...arr
                ]);

                return google.visualization.data.group(dt, [0], [
                    {
                        column: 1,
                        aggregation: google.visualization.data.sum,
                        type: 'number'
                    }
                ]);
            }

            function createDataForTimeline() {
                let arr = [];
                for (let hit of hits) {
                    arr.push([new Date(hit.hit_timestamp), 1]);
                }

                let dt = google.visualization.arrayToDataTable([
                    ['Time', 'Hits'],
                    ...arr
                ]);

                return google.visualization.data.group(dt, [0], [
                    {
                        column: 1,
                        aggregation: google.visualization.data.sum,
                        type: 'number'
                    }
                ]);
            }

            function recogDrawBarChart() {
                let data = createData(1, ['Age Group', 'Hits']);

                let options = {
                    title: 'Demographics',
                    width: '810',
                    height: '500',
                    chartArea: {width: '50%'},
                    colors: ['#FF7300', '#383A38', '#FFC799'],
                    hAxis: {
                        title: 'Age Groups',
                        minValue: 0
                    },
                    vAxis: {
                        title: 'Hits'
                    },
                    orientation: 'horizontal',
                    legend: { position: 'none' }
                };

                let chart = new google.visualization.BarChart(document.getElementById('recog-age-graph'));
                chart.draw(data, options);
            }

            function recogDrawPieChart() {
                let data = createData(2, ['Gender', 'Hits']);

                // Set chart options
                let options = {
                    title:'Gender',
                    width: '810',
                    height: '500',
                    colors: ['#FF7300', '#383A38']
                };

                // Instantiate and draw our chart, passing in some options.
                let chart = new google.visualization.PieChart(document.getElementById('recog-gender-graph'));
                chart.draw(data, options);
            }

            function recogDrawLineChart() {
                let data = createDataForTimeline();

                let options = {
                    title: 'Timestamp',
                    curveType: 'function',
                    width: '1618',
                    height: '500',
                    legend: {position: 'none'},
                    colors: ['#FF7300'],
                    explorer: {
                        axis: 'horizontal',
                        actions: ['dragToZoom', 'rightClickToReset']
                    },
                    vAxis: {
                        minValue: 0
                    },
                    gridlines: { count: -1},
                    library: {hAxis: { format: "hh. mm." } }
                };

                let chart = new google.visualization.LineChart(document.getElementById('recog-time-graph'));

                let formatter = new google.visualization.DateFormat({formatType: 'long'});

                formatter.format(data, 0);

                chart.draw(data, options);
            }
        }())
    </script>
@endsection

@section('styles')
    <style>
        .panel-body {
            position: relative;
        }

        .panel {
            border: none;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.8);
            height: 100%;
            width: 100%;
            z-index: 1000;
        }

        .overlay-content {
            font-size: 20px;
            color: #fff;
            text-align: center;
            position: inherit;
            top: 20%;
            left: 50%;
            transform: translate(-50%, 0);
        }

        .overlay-content i {
            font-size: 100px;
        }
    </style>
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

                        <div class="overlay">
                            <div class="overlay-content">
                                <i class="fa fa-pulse fa-spinner"></i> <br>
                                Please wait while we create a visualization for your data. <br/>
                                The speed of calculation will vary depending on the internet connection and amount of data.
                            </div>
                        </div>

                        <h1>Project Overview</h1>
                        <hr>

                        <div class="content-body">
                            <div class="other-graphs">
                                <div class="graph" id="recog-gender-graph"></div>
                                <div class="graph" id="recog-age-graph" style="background-color: #da7c29;"></div>
                            </div>

                            <div class="time-and-video">
                                <div class="time-graph" id="recog-time-graph"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="projectId" value="{{ $project->id }}">
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
                                        <th>Project Type</th>
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
                                            <th>{{ $location['project_type'] }}</th>
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