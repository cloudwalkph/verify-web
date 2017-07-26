@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="/dropzone/basic.css">
    <link rel="stylesheet" href="/dropzone/dropzone.css">
@endsection

@section('content')
    <div class="info-section">
        <div class="info-title">
            <a href="/projects/{{ $project->id }}" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
            <h1 style="color: #fff">
                {{ $project->name }}
                <p class="info-sub-title">{{ $location->name }}</p>
            </h1>

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

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="content">
                            <h3>Event Analytics</h3>
                            <p>Real time Data from <strong>{{ $project->name }}</strong> activities.</p>
                            <p style="color: #FF7300;">Last updated: {{ $project->updated_at->toFormattedDateString() }}</p>

                            <ul class="nav nav-tabs" id="serviceTabs">
                                <li class="active"><a href="#video" data-toggle="tab">Video</a></li>
                                <li><a href="#event-analytics" data-toggle="tab">Manual</a></li>
                                <li class="{{ $services && in_array('automatic', $services) ? '' : 'hide' }}"><a href="#automatic-data" data-toggle="tab">Automated</a></li>
                                <li class="{{ $services && in_array('gps', $services) ? '' : 'hide' }}"><a href="#gps-data" data-toggle="tab">GPS</a></li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="video">
                                    <div class="content-body">
                                        <div class="time-and-video">
                                            <select name="videos" id="video-selection" class="form-control">
                                                @foreach ($videos as $video)
                                                    <option value="{{ $video->name }}" data-status="{{ $video->status }}">{{ $video->alias }}</option>
                                                @endforeach
                                            </select>

                                            <div class="video-feed bmpui-flexbox" id="player">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="event-analytics">
                                    <div class="content-body">
                                        <div class="time-and-video">
                                            {{--<select name="videos" id="video-selection" class="form-control">--}}
                                                {{--@foreach ($videos as $video)--}}
                                                    {{--<option value="{{ $video->name }}" data-status="{{ $video->status }}">{{ $video->alias }}</option>--}}
                                                {{--@endforeach--}}
                                            {{--</select>--}}

                                            <div class="time-graph" id="time-graph"></div>
                                            {{--<div class="video-feed bmpui-flexbox" id="player">--}}
                                            </div>
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
                                            <div class="time-graph" id="recog-time-graph"></div>
                                        </div>

                                        <div class="other-graphs">
                                            <div class="graph" id="recog-gender-graph"></div>
                                            <div class="graph" id="recog-age-graph" style="background-color: #da7c29;"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12">

                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <th>Timestamp</th>
                                                    <th>Image</th>
                                                    <th>Demographics</th>
                                                    <th>Gender</th>
                                                </tr>
                                            @foreach($auto as $hit)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::createFromTimestamp(strtotime($hit->hit_timestamp))->toDateTimeString() }}</td>
                                                    <td><img src="{{ Storage::drive('s3')->url($hit->image) }}" height="50" width="50" class="img-circle" alt=""></td>
                                                    @foreach($hit->answers as $answer)
                                                        <td>{{ $answer->value }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                        <div class="pull-right">
                                            {{ $auto->links() }}
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="gps-data">
                                    <div class="content-body">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="ba">&nbsp;&nbsp;</label>
                                                    <select name="ba" id="ba" class="form-control">
                                                        <option value="0" selected>Select person involved in project</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label for="">&nbsp;</label>
                                                    <button class="btn btn-primary">Show History</button>
                                                </div>
                                            </div>

                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label for="">&nbsp;</label>
                                                    <button class="btn btn-default">Show Current Location</button>
                                                </div>
                                            </div>
                                        </div>
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
    {{--<script src="//content.jwplatform.com/libraries/PotMeZLE.js"></script>--}}
    <script src="/dropzone/dropzone.js"></script>
    <script type="text/javascript" src="//bitmovin-a.akamaihd.net/bitmovin-player/stable/7/bitmovinplayer.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpj9D7dDRll2Cj-sTXzPEVwoCwx7LOjXw"
            async defer></script>

    <script>
        let conf = {
            key:       "58b50672-0aa2-4dd2-982c-412d99df04c4",
            source: {
                dash:        "//streamer.medix.ph/{{ $location->assigned_raspberry }}/manifest.mpd",
                hls:         "//streamer.medix.ph/{{ $location->assigned_raspberry }}/playlist.m3u8",
                poster:      "/images/logo-verify.png"
            }
        };

        let player = bitmovin.player("player");
        player.setup(conf).then(function(value) {
            // Success
            console.log("Successfully created bitmovin player instance");
        }, function(reason) {
            // Error!
            console.log("Error while creating bitmovin player instance");
        });

        $('#video-selection').on('change', function() {
            let status = $(this).find(':selected').data('status');
            let value = $(this).val();

            loadMPD(true, status, value);
        });

        function loadMPD(a, status, file) {
            let videoUrl = '';

            switch (status) {
                case "live":
                    videoUrl = `//streamer.medix.ph/live/${file}`;
                    break;
                case "playback":
                    videoUrl = `//streamer.medix.ph/vods3/_definst_/mp4:amazons3/verify-bucket/playback/${file}`;
                    break;
                default:
                    videoUrl = '';
            }

            let source = {
                dash:        `${videoUrl}/manifest.mpd`,
                hls:         `${videoUrl}/playlist.m3u8`,
                poster:      "/images/logo-verify.png"
            };

            console.log('current video', source);

            player.load(source);
        }
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
    <script>
        $(function() {
            let map, activations;

            function initMap() {
                // Create the map with no initial style specified.
                // It therefore has default styling.
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: 14.6334979, lng: 121.0561233},
                    zoom: 19,
                    mapTypeControl: false
                });

                let contentString = '<div id="iw-container">' +
                    '<div class="iw-title">Test Project</div>' +
                    '<div class="iw-content">' +
                    '<div class="iw-subTitle">Rina Martes</div>' +
                    '</div>' +
                    '<div class="iw-bottom-gradient"></div>' +
                    '</div>';

                let infowindow = new google.maps.InfoWindow({
                    content: contentString
                });


                let coordinates = [
                    [14.629355482536468, 121.04045122861862],
                    [14.629334720523334, 121.04066580533981],
                    [14.629223124669092, 121.04064166545868],
                    [14.62967210299344, 121.04227244853973],
                    [14.630416937841238, 121.04489028453827],
                    [14.630671271112796, 121.04571640491486],
                    [14.631247412739997, 121.04766100645065],
                    [14.631553649565866, 121.04902356863022],
                    [14.632340001491867, 121.05148583650589],
                    [14.633178254732638, 121.05436384677887],
                    [14.633808888961013, 121.0565310716629],
                    [14.63361165375927, 121.05664908885956],
                    [14.633546773719932, 121.05656325817108],
                    [14.6336324153678, 121.05653375387192],
                    [14.63363176656757, 121.05660617351532],
                    [14.63358764814696, 121.05660684406757]
                ];

                let baCoordinates = [];

                let baPath = new google.maps.Polyline({
                    geodesic: true,
                    strokeColor: '#ff9e4a',
                    strokeOpacity: 0.9,
                    strokeWeight: 3
                });

                google.maps.event.addListenerOnce(map, 'idle', function() {
                    let image = '/images/marker.png';
                    activations = new google.maps.Marker({
                        map: map,
                        icon: image,
                        animation: google.maps.Animation.DROP,
                        title: 'Test Project'
                    });

                    activations.addListener('click', function() {
                        infowindow.open(map, activations);
                        toggleBounce();
                    });

                    let step = 0;
                    let numSteps = coordinates.length - 1;
                    let timePerStep = 2000;
                    let interval = setInterval(function() {
                        step += 1;

                        if (step >= numSteps) {
                            clearInterval(interval);
                        } else {
                            let coord = {
                                lat: coordinates[step][0],
                                lng: coordinates[step][1]
                            };

                            baCoordinates.push(coord);

                            map.setCenter(coord);
                            activations.setPosition(coord);
                            baPath.setPath(baCoordinates);
                        }
                    }, timePerStep);
                });

                baPath.setMap(map);

                google.maps.event.addListener(map, "click", function (e) {

                    //lat and lng is available in e object
                    let latLng = e.latLng;
                    console.log(latLng.toString());
                });

                // *
                // START INFOWINDOW CUSTOMIZE.
                // The google.maps.event.addListener() event expects
                // the creation of the infowindow HTML structure 'domready'
                // and before the opening of the infowindow, defined styles are applied.
                // *
                google.maps.event.addListener(infowindow, 'domready', function() {

                    // Reference to the DIV that wraps the bottom of infowindow
                    let iwOuter = $('.gm-style-iw');

                    /* Since this div is in a position prior to .gm-div style-iw.
                     * We use jQuery and create a iwBackground variable,
                     * and took advantage of the existing reference .gm-style-iw for the previous div with .prev().
                     */
                    let iwBackground = iwOuter.prev();

                    // Removes background shadow DIV
                    iwBackground.children(':nth-child(2)').css({'display' : 'none'});

                    // Removes white background DIV
                    iwBackground.children(':nth-child(4)').css({'display' : 'none'});

                    // Moves the infowindow 115px to the right.
                    iwOuter.parent().parent().css({left: '115px'});

                    // Moves the shadow of the arrow 76px to the left margin.
                    iwBackground.children(':nth-child(1)').attr('style', function(i,s){ return s + 'left: 76px !important;'});

                    // Moves the arrow 76px to the left margin.
                    iwBackground.children(':nth-child(3)').attr('style', function(i,s){ return s + 'left: 76px !important;'});

                    // Changes the desired tail shadow color.
                    iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': 'rgba(72, 181, 233, 0.6) 0px 1px 6px', 'z-index' : '1'});

                    // Reference to the div that groups the close button elements.
                    let iwCloseBtn = iwOuter.next();

                    // Apply the desired effect to the close button
                    iwCloseBtn.css({opacity: '1', right: '38px', top: '3px', border: '7px solid #48b5e9', 'border-radius': '13px', 'box-shadow': '0 0 5px #3990B9'});

                    // If the content of infowindow not exceed the set maximum height, then the gradient is removed.
                    if($('.iw-content').height() < 140){
                        $('.iw-bottom-gradient').css({display: 'none'});
                    }

                    // The API automatically applies 0.7 opacity to the button after the mouseout event. This function reverses this event to the desired value.
                    iwCloseBtn.mouseout(function(){
                        $(this).css({opacity: '1'});
                    });
                });
            }

            function toggleBounce() {
                if (activations.getAnimation() !== null) {
                    activations.setAnimation(null);
                } else {
                    activations.setAnimation(google.maps.Animation.BOUNCE);
                }
            }

            $('#serviceTabs a').click(function (e) {
                e.preventDefault();

                if (! map) {
                    initMap();
                }
            })
        })
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
