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
                            <h3>Event Analytics</h3>
                            <p>Real time Data from <strong>{{ $project->name }}</strong> activities.</p>
                            <p style="color: #FF7300;">Last updated: {{ $project->updated_at->toFormattedDateString() }}</p>

                            <ul class="nav nav-tabs" id="serviceTabs">
                                <li>
                                    <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}">Manual</a>
                                </li>

                                <li class="{{ $services && in_array('automatic', $services) ? '' : 'hide' }}">
                                    <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/automated">Automated</a>
                                </li>

                                <li class="{{ $services && in_array('gps', $services) ? '' : 'hide' }}">
                                    <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/gps">GPS</a>
                                </li>

                                <li class="{{ count($videos) <= 0 ? 'hide' : '' }} active"><a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/videos">Video</a></li>
                            </ul>

                            <div class="content-body">
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
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="//bitmovin-a.akamaihd.net/bitmovin-player/stable/7/bitmovinplayer.js"></script>

    <script>
        let conf = {
            key:       "58b50672-0aa2-4dd2-982c-412d99df04c4",
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

            let status = $('#video-selection').find(':selected').data('status');
            let value  = $('#video-selection').val();

            loadMPD(true, status, value);
        }
    </script>
@endsection
