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
        <div class="col-md-12 text-right" style="margin-top: 10px">
            <a href="javascript:window.print()" class="btn btn-primary print-btn" > <i class="fa fa-print fa-lg"></i> Print Report</a>
        </div>
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

        <div class="time-and-video">
            <div class="col-md-12">
                <div class="graph-description-container">
                    <h2>Timestamp</h2>
                    <p class="help-block">Data or hits recorded during specific hours of the day or run.</p>
                </div>
                <div id="time-graph"></div>
            </div>
        </div>
    </div>

</div>

@section('scripts')
    @parent

    <script type="text/javascript">
        Verify.Chart.init({{ $location && isset($location['id']) ? $location['id'] : null }});
    </script>
@endsection