@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="/dropzone/basic.css">
    <link rel="stylesheet" href="/dropzone/dropzone.css">
    <style>
        .black-description ul li {
            list-style: none;
            width: 50%;
            float: left;
        }
        .black-description ul {
            padding: 0;
            width:50%;
        }
        .black-description {
            color: #fff;
            padding: 20px 30px 250px;
        }
        .black-description b {
            color: #B4B4B4;
        }
    </style>
@endsection

@section('content')
    <div class="info-section">
        <div class="info-title">
            <div class="col-sm-7" style="display: inline-flex;">
                <a href="/projects/{{ $project->id }}" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
                <h1 style="color: #fff">
                    {{ $project->name }}
                    <p class="info-sub-title">{{ $location->name }}</p>
                </h1>
            </div>
            <div class="col-sm-3">
                <h5 style="color: #B4B4B4;"><b>Reported Hits:</b></h5>
                <h5 class="text-primary">{{ $location->manual_hits }}</h5>
            </div>
        </div>

        @if ($services && in_array('automatic', $services))
            <div class="info-body">
                <a href="#"
                   data-toggle="modal"
                   data-target="#uploadImages"
                   class="btn btn-primary">Upload Images Manually</a>
            </div>

            <div class="modal fade" id="uploadImages" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" style="width: 80%" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Upload Face Images</h4>
                        </div>
                        <div class="modal-body">
                            <form class="dropzone" id="myDropzone" action="/projects/{{ $project->id }}/locations/{{ $location->id }}/faces" method="POST">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @endif

        <div class="info-body">
            <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/event-reports"
               class="btn btn-primary">Verify Audit Report</a>
        </div>
    </div>

    <div class="black-description">
        <div class="col-md-4 col-sm-6 col-xs-12">
            <h4><b>Type:</b> {{ ($location->project_type) ? $location->project_type : 'NA' }} </h4>
            <h4><b>Target Hits:</b> {{ ($location->target_hits > 0) ? $location->target_hits.' Hits' : 'No target hits' }} </h4>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
            <h4><b>Run Date:</b> {{ $location->date }} </h4>
            <h4><b>Team Leader:</b> Jane Doe </h4>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
            <h4><b>Brand Ambassadors:</b> </h4>
            <ul>
                <li>Suzan</li>
                <li>Jerry</li>
                <li>Sandra</li>
            </ul>
        </div>

    </div>

    <div class="container-fluid" style="margin-top: -130px">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="content">
                            <h3>Event Analytics</h3>
                            <p>Real time Data from <strong>{{ $project->name }}</strong> activities.</p>

                            <ul class="nav nav-tabs" id="serviceTabs">
                                <li class="active">
                                    <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}">Manual</a>
                                </li>

                                <li class="{{ $services && in_array('automatic', $services) ? '' : 'hide' }}">
                                    <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/automated">Automated</a>
                                </li>

                                <li class="{{ $services && in_array('gps', $services) ? '' : 'hide' }}">
                                    <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/gps">GPS</a>
                                </li>

                                <li class="{{ count($videos) <= 0 ? 'hide' : '' }}"><a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/videos">Video</a></li>
                            </ul>

                            <div class="content-body">
                                <div class="other-graphs">
                                    <div class="graph" id="gender-graph"></div>
                                    <div class="graph" id="age-graph" style="background-color: #da7c29;"></div>
                                </div>

                                <div class="time-and-video">
                                    <div class="time-graph" id="time-graph"></div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/dropzone/dropzone.js"></script>

    <script type="text/javascript">
        (function() {
            let answers = JSON.parse('{!! json_encode($answers) !!}');
            let hits = JSON.parse('{!! json_encode($hits) !!}');

            // Load the Visualization API and the corechart package.
            google.charts.load('current', {'packages':['corechart']});

            // Set a callback to run when the Google Visualization API is loaded.
            google.charts.setOnLoadCallback(drawCharts);

            function drawCharts() {
                drawLineChart();
                drawPieChart();
                drawBarChart();
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
                    console.log(new Date(hit.hit_timestamp));
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

            let channel = 'location.{{ $location->id }}';
            Echo.private(channel)
                .listen('NewHitCreated', (e) => {
                    console.log(e);
                    for (let answer of e.hit.answers) {
                        answers.push(answer);
                    }

                    hits.push(e.hit);

                    drawBarChart();
                    drawPieChart();
                    drawLineChart();
                });

            function drawBarChart() {
                let data = createData(1, ['Age Group', 'Hits']);

                let options = {
                    title: 'Demographics',
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

                let chart = new google.visualization.BarChart(document.getElementById('age-graph'));
                chart.draw(data, options);
            }

            function drawPieChart() {
                let data = createData(2, ['Gender', 'Hits']);

                // Set chart options
                let options = {
                    title:'Gender',
                    colors: ['#FF7300', '#383A38']
                };

                // Instantiate and draw our chart, passing in some options.
                let chart = new google.visualization.PieChart(document.getElementById('gender-graph'));
                chart.draw(data, options);
            }

            function drawLineChart() {
                let data = createDataForTimeline();

                let options = {
                    title: 'Timestamp',
                    curveType: 'function',
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

                let chart = new google.visualization.LineChart(document.getElementById('time-graph'));

                let formatter = new google.visualization.DateFormat({formatType: 'long'});

                formatter.format(data, 0);

                chart.draw(data, options);
            }
        }())
    </script>

    <script>
        Dropzone.options.myDropzone = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 2, // MB
            acceptedFiles: 'image/*',
            accept: function(file, done) {
                if (file.name == "justinbieber.jpg") {
                    done("Naha, you don't.");
                }

                else { done(); }
            },
            init: function() {
                this.on('sending', function(file, xhr, formData) {
                    formData.append('_token', window.Laravel.csrfToken);
                })
            }
        };
    </script>
@endsection
