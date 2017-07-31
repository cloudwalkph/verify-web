<div class="black-description project-location">
    <div class="col-md-4 col-sm-6 col-xs-12">
        <h4><b>Type:</b> {{ ($location->project_type) ? $location->project_type : 'NA' }} </h4>
        <h4><b>Target Hits:</b> {{ ($location->target_hits > 0) ? $location->target_hits.' Hits' : 'No target hits' }} </h4>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <h4><b>Run Date:</b> {{ $location->date }} </h4>
        <h4><b>Team Leader:</b>  </h4>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <h4><b>Brand Ambassadors:</b> </h4>
        <ul>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>

</div>