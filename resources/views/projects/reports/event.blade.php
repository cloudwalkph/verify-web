@extends('layouts.app')

@section('content')

    <div class="info-section">
        <div class="info-title">
            <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
            <h1 style="color: #fff">
                {{ $project->name }}
                <p class="info-sub-title">{{ $location->name }}</p>
            </h1>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-default" style="min-height: 1400px;">
                    <div class="panel-body">
                        <div class="content">
                            <h3 style="margin: 30px 0;">Event Report</h3>
                            <p style="margin-bottom: 30px; line-height: 30px">
                                Shows all the complete data and information gathered and recorded during the running of this event or project.
                            </p>
                            <button type="button" class="btn btn-primary" style="margin-bottom: 20px" onclick="frames['frameEvent'].print()">Print Report</button>
                            <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/audit-reports"
                               class="btn btn-primary">Audit Report</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="content">
                            <div class="image-container">
                                <img src="{{ asset('images/verify_white.png') }}" alt="logo" style="height: 100px">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <h2>{{ $project->name }}</h2>
                                    <p>{{ $location->name }}</p>
                                </div>
                                <div class="col-md-2" style="margin-top: 15px">
                                    <h5>Status:</h5>
                                    <p class="text-primary">{{ $location->status }}</p>
                                </div>
                                <div class="col-md-2" style="margin-top: 15px">
                                    <h5>Last Updated:</h5>
                                    <p class="text-primary">{{ count($location->hits) > 0 ? $location->hits[count($location->hits) - 1]->created_at->toFormattedDateString() : 'N/A' }}</p>
                                </div>

                                <div class="col-md-2" style="margin-top: 15px">
                                    <h5>Achieved Target Hits:</h5>
                                    <p class="text-primary">{{ $location->hits()->count() }} / {{ $location->target_hits }}</p>
                                </div>
                                <div class="col-md-12"><hr></div>

                                <div class="col-md-12">
                                    <h2>Event Analytics</h2>
                                    <p>Real time Data from <strong>{{ $project->name }}</strong> activities.</p>
                                </div>
                            </div>

                            <div class="content-body">
                                <div class="time-and-video">
                                    <div class="time-graph" id="time-graph"></div>
                                </div>

                                <div class="other-graphs">
                                    <div class="graph" id="gender-graph"></div>
                                    <div class="graph" id="age-graph" style="background-color: #da7c29;"></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    <iframe src="/projects/{{ $project->id }}/locations/{{ $location->id }}/event-reports/preview" name="frameEvent" style="width: 0; height: 0"></iframe>
@endsection

@section('scripts')
    <script src="//content.jwplatform.com/libraries/PotMeZLE.js"></script>

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
                    }
                };

                let chart = new google.visualization.LineChart(document.getElementById('time-graph'));

                let formatter = new google.visualization.DateFormat({formatType: 'long'});

                formatter.format(data, 0);

                chart.draw(data, options);
            }
        }())
    </script>
@endsection