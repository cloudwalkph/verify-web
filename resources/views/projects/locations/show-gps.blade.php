@extends('layouts.app')

@section('styles')
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
            <div class="col-sm-6" style="display: inline-flex;">
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
                            <h3>GPS Data</h3>
                            <p>Location History of users</p>

                            <ul class="nav nav-tabs" id="serviceTabs">
                                <li>
                                    <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}">Manual</a>
                                </li>

                                <li class="{{ $services && in_array('automatic', $services) ? '' : 'hide' }}">
                                    <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/automated">Automated</a>
                                </li>

                                <li class="{{ $services && in_array('gps', $services) ? '' : 'hide' }} active">
                                    <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/gps">GPS</a>
                                </li>

                                <li class="{{ count($videos) <= 0 ? 'hide' : '' }}"><a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/videos">Video</a></li>
                            </ul>

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
@endsection

@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpj9D7dDRll2Cj-sTXzPEVwoCwx7LOjXw&callback=initMap"
            async defer></script>

    <script>
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
    </script>
@endsection
