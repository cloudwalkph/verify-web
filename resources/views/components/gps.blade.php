<div class="overlay">
    <div class="overlay-content">
        <i class="fa fa-pulse fa-spinner"></i> <br>
        Please wait while we create a visualization for your data. <br/>
        The speed of calculation will vary depending on the internet connection and amount of data.
    </div>
</div>

<div class="content">
    <h3>GPS Data</h3>
    <p>Location History of users</p>

    <ul class="nav nav-tabs" id="serviceTabs">
        {{ $nav }}
    </ul>

    <div class="content-body" style="position: relative">
        <div id="mapTime">
            <h1 class="text-primary">00:00:00</h1>
        </div>

        <div id="map"></div>
    </div>

</div>

@section('scripts')
    @parent

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpj9D7dDRll2Cj-sTXzPEVwoCwx7LOjXw&callback=Verify.GPS.initMap"
            async defer></script>

    <script>
        $(function() {
            Verify.GPS.getLocationData({{ $project['id'] }}, {{ $location['id'] }})
        });
    </script>
@endsection