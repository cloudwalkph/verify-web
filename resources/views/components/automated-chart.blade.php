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

        {{--<div class="overlay">--}}
            {{--<div class="overlay-content">--}}
                {{--<i class="fa fa-pulse fa-spinner"></i> <br>--}}
                {{--Please wait while we create a visualization for your data. <br/>--}}
                {{--The speed of calculation will vary depending on the internet connection and amount of data.--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--@if($timeandvideo)--}}
            {{--<div class="time-and-video">--}}
                {{--{{ $timeandvideo }}--}}
            {{--</div>--}}
        {{--@endif--}}

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