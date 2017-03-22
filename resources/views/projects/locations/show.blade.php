@extends('layouts.app')

@section('content')
    <div class="info-section">
        <div class="info-title">
            <a href="/projects/{{ $project->id }}" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
            <h1 style="color: #fff">
                {{ $project->name }}
                <p class="info-sub-title">{{ $location->name }}</p>
            </h1>

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

                        <div class="content">
                            <div class="description">
                                <h3>Event Analytics</h3>
                            </div>

                            <div class="content-body">
                                <div class="time-and-video">
                                    <div class="time-graph" id="time-graph"></div>
                                    <div class="video-feed" id="player">
                                    </div>
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
    </div>
@endsection

@section('scripts')
    <script src="//content.jwplatform.com/libraries/PotMeZLE.js"></script>
    <script>
        (function() {
            let player = jwplayer('player');
            let liveUrl = "rtmp://54.238.155.160/{{ 'vod/raspi-1-24-Jan-17-06:31:03.flv' }}";

            player.setup({
                file: liveUrl,
                image: "/images/logo-verify.png"
            });

            player.addButton(
                //This portion is what designates the graphic used for the button
                "//icons.jwplayer.com/icons/white/download.svg",
                //This portion determines the text that appears as a tooltip
                "Download Video",
                //This portion designates the functionality of the button itself
                function() {
                    //With the below code, we're grabbing the file that's currently playing
                    window.location.href = player.getPlaylistItem()['file'];
                },
                //And finally, here we set the unique ID of the button itself.
                "download"
            );
        }())
    </script>

    <script type="text/javascript">
        (function() {
            // Load the Visualization API and the corechart package.
            google.charts.load('current', {'packages':['corechart']});

            // Set a callback to run when the Google Visualization API is loaded.
            google.charts.setOnLoadCallback(drawCharts);

            function drawCharts() {
                drawLineChart();
                drawPieChart();
                drawBarChart();
            }


            function drawBarChart() {
                let data = google.visualization.arrayToDataTable([
                    ['City', 'Hits'],
                    ['15 - 20', 8175000],
                    ['21 - 25', 3792000],
                    ['26 - 30', 2695000],
                    ['31 - 35', 2099000],
                ]);

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
                chart.draw(data, options)
            }

            function drawPieChart() {

                // Create the data table.
                let data = new google.visualization.DataTable();
                data.addColumn('string', 'Gender');
                data.addColumn('number', 'Hits');
                data.addRows([
                    ['Male', 3],
                    ['Female', 3]
                ]);

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
                let data = google.visualization.arrayToDataTable([
                    ['Year', 'Hits'],
                    ['2004', 400],
                    ['2005', 460],
                    ['2006', 1120],
                    ['2007', 540]
                ]);

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

                chart.draw(data, options);
            }
        }())
    </script>
@endsection
