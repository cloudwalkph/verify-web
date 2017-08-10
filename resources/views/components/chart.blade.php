<div class="content">
    @if (! $title)
        <h3>Analytics</h3>
        <p>Real time Data from <strong>{{ $project->name }}</strong> activities.</p>
    @else
        {{ $title }}
    @endif

    <input type="hidden" id="projectId" value="{{ $project->id }}">

    <ul class="nav nav-tabs" id="serviceTabs">
        {{ $nav }}
    </ul>

    <div class="content-body" style="position: relative">
        @if($ongoingReport)
            <div class="col-md-12" style="margin-top: 20px">
                {{ $ongoingReport }}
            </div>
        @endif

        <div class="overlay">
            <div class="overlay-content">
                <i class="fa fa-pulse fa-spinner"></i> <br>
                Please wait while we create a visualization for your data. <br/>
                The speed of calculation will vary depending on the internet connection and amount of data.
            </div>
        </div>

        @if($timeandvideo)
            <div class="time-and-video">
                {{ $timeandvideo }}
            </div>
        @endif

        <div class="other-graphs">
            <div class="col-md-6 col-xs-6">
                <div class="graph-description-container">
                    <h2>Gender</h2>
                    <p class="help-block">The percentage of male and female consumers from the total number of hits recorded.</p>
                </div>
                <div id="gender-graph"></div>
            </div>
            <div class="col-md-6 col-xs-6">
                <div class="graph-description-container">
                    <h2>Age Group</h2>
                    <p class="help-block">The distribution of hits coming various age groups from the total number of hits recorded.</p>
                </div>
                <div id="age-graph"></div>
            </div>
        </div>

    </div>

</div>

@section('scripts')
    @parent

    <script type="text/javascript">
        Verify.Chart.init({{ $location && isset($location['id']) ? $location['id'] : null }});
    </script>

    <script type="text/javascript" src="//bitmovin-a.akamaihd.net/bitmovin-player/stable/7/bitmovinplayer.js"></script>

    <script>
        let conf = {
            key:       "58b50672-0aa2-4dd2-982c-412d99df04c4",
        };

        let player = bitmovin.player("player");
        if (typeof player !== 'undefined') {
            player.setup(conf).then(function(value) {
                // Success
                console.log("Successfully created bitmovin player instance");
            }, function(reason) {
                // Error!
                console.log("Error while creating bitmovin player instance");
            });
        }

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
                    videoUrl = null;
            }

            if (videoUrl === null) {
                return;
            }

            let source = {
                dash:        `${videoUrl}/manifest.mpd`,
                hls:         `${videoUrl}/playlist.m3u8`,
                poster:      "/images/logo-verify.png"
            };

            console.log('current video', source);

            player.load(source);
        }

        let status = $('#video-selection').find(':selected').data('status');
        let value  = $('#video-selection').val();

        loadMPD(true, status, value);
    </script>

@endsection