@extends('layouts.app')

@section('scripts')
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

                    if (hits.length > 0) {
                        drawCharts();
                    }

                    $('.overlay').addClass('hide');
            }));

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
            <div class="col-md-5 col-sm-6" style="display: inline-flex;">
                <a href="/home" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
                <h1 style="color: #fff">
                    {{ $project->name }}</h1>
            </div>
            <div class="col-sm-3">
                <h5 style="color: #B4B4B4;"><b>Runs Completed:</b></h5>
                <h5 class="text-primary">{{ $completed }} / {{ count($project['locations']) }} ({{ ($completed / count($project['locations'])) * 100 }}%)</h5>
            </div>
            <div class="col-sm-3">
                <h5 style="color: #B4B4B4;"><b>Reported Hits:</b></h5>
                <h5 class="text-primary">{{ $reported }}</h5>
            </div>
        </div>

        <div class="info-body">
            <button class="btn btn-primary">Verify Audit Report</button>
        </div>
    </div>

    <div class="black-description">
        <ul class="nav nav-pills">
            <li role="presentation" class="active"><a href="/projects/{{ $project->id }}">Overview</a></li>
            <li role="presentation"><a href="/projects/{{ $project->id }}/locations">Locations</a></li>
        </ul>
    </div>

    <div class="container-fluid" style="margin-top: -130px">
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
    </div>
    <input type="hidden" id="projectId" value="{{ $project->id }}">
@endsection
