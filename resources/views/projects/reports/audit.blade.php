@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-default" style="min-height: 1560px;">
                    <div class="panel-body">
                        <div class="content">
                            <h3 style="margin: 30px 0;">Event Report</h3>
                            <p style="margin-bottom: 30px; line-height: 30px">
                                Lorem Khaled Ipsum is a major key to success. Stay
                                focused. I’m up to something. Look at the sunset,
                                life is amazing, life is beautiful,
                            </p>
                            <button type="button" class="btn btn-primary">Print Report</button>
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
                                    <p class="text-primary">Completed</p>
                                </div>
                                <div class="col-md-2" style="margin-top: 15px">
                                    <h5>Last Updated:</h5>
                                    <p class="text-primary">{{ $project->updated_at->toFormattedDateString() }}</p>
                                </div>
                                <div class="col-md-2" style="margin-top: 15px">
                                    <h5>Runs Completed:</h5>
                                    <p class="text-primary">500 of 1000 (50%)</p>
                                </div>
                                <div class="col-md-2" style="margin-top: 15px">
                                    <h5>Achieved Target Hits:</h5>
                                    <p class="text-primary">500,000 of 1,000,000 (50%)</p>
                                </div>
                                <div class="col-md-12"><hr></div>

                                <div class="col-md-12">
                                    <h3>Event Analytics</h3>
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

                            <p class="text-center" style="color: #B4B4B4;line-height: 30px;margin: 40px;">
                                Lorem Khaled Ipsum is a major key to success. Stay focused. I’m up to something. Look at the sunset, life is amazing, life is beautiful, life is what you make it.
                                The key to success is to keep your head above the water, never give up. You see that bamboo behind me though, you see that bamboo? Ain’t nothin’ like bamboo.
                                Bless up. They don’t want us to win. Eliptical talk. Celebrate success right, the only way, apple.
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
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
