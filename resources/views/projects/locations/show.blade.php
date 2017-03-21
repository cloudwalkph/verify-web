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
                                    <div class="time-graph"></div>
                                    <div class="video-feed" id="player">
                                    </div>
                                </div>

                                <div class="other-graphs">
                                    <div class="graph" style="background-color: #da7c29;"></div>
                                    <div class="graph"></div>
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
        var player = jwplayer('player');
        var liveUrl = "rtmp://54.238.155.160/{{ 'vod/raspi-1-24-Jan-17-06:31:03.flv' }}";

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
    </script>
@endsection
