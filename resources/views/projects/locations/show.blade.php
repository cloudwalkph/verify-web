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
            <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/event-reports"
               class="btn btn-primary">Verify Audit Report</a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="content">
                            <h3>Event Analytics</h3>
                            <p>Real time Data from <strong>{{ $project->name }}</strong> activities.</p>
                            <p style="color: #FF7300;">Last updated: {{ $project->updated_at->toFormattedDateString() }}</p>

                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#event-analytics" data-toggle="tab">Event Analytics</a></li>
                                <li><a href="#automatic-data" data-toggle="tab">Face Recognition Data</a></li>
                                <li><a href="#gps-data" data-toggle="tab">GPS Data</a></li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="event-analytics">
                                    <div class="content-body">
                                        <div class="time-and-video">
                                            <div class="time-graph" id="time-graph"></div>
                                            <div class="video-feed" id="player"></div>
                                        </div>

                                        <div class="other-graphs">
                                            <div class="graph" id="gender-graph"></div>
                                            <div class="graph" id="age-graph" style="background-color: #da7c29;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="automatic-data">
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

                                <div class="tab-pane" id="gps-data">
                                    <div class="content-body">
                                        <div id="map"></div>
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
            let liveUrl = "http://streamer.medix.ph:1935/{{ $location->assigned_raspberry }}/playlist.m3u8";
console.log(liveUrl);
            player.setup({
                sources: [
                    {
                        file: "rtmp://streamer.medix.ph:1935/{{ $location->assigned_raspberry }}"
                    }
                ],
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
            $("#player").parent().append('<select class="form-control" id="video-selection"><option>Video 1</option><option>Video 2</option><option>Video 3</option></select>');
        }())
    </script>

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
        let map;

        function initMap() {
            // Create the map with no initial style specified.
            // It therefore has default styling.
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: -33.86, lng: 151.209},
                zoom: 13,
                mapTypeControl: false
            });
        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpj9D7dDRll2Cj-sTXzPEVwoCwx7LOjXw&callback=initMap"
            async defer></script>
@endsection
