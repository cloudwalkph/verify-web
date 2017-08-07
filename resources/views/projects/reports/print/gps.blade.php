<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <style>
        th, td {
            vertical-align: middle !important;
            font-size: 11px !important;
        }
    </style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AIMS') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    <script type="text/javascript" src="//www.gstatic.com/charts/loader.js"></script>
</head>
<body>


    <div class="content">
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-primary"> <i class="fa fa-print fa-lg"></i> Print Report</button>
            </div>
        </div>
        <div class="image-container">
            <img src="{{ asset('images/verify_white.png') }}" alt="logo" style="height: 100px">
        </div>
        <div class="row">
            <div class="col-md-4 col-xs-12">
                <h2>{{ $project->name }}</h2>
                <p>{{ $location->name }}</p>
            </div>
            <div class="col-md-2 col-xs-4" style="margin-top: 15px">
                <h5>Status:</h5>
                <p class="text-primary">{{ $location->status == 'completed' ? 'Achieved' : ucwords($location->status) }}</p>
            </div>
            <div class="col-md-2 col-xs-4" style="margin-top: 15px">
                <h5>Event Date:</h5>
                <p class="text-primary">{{ count($location->hits) > 0 ? $location->hits[count($location->hits) - 1]->created_at->toFormattedDateString() : 'N/A' }}</p>
            </div>

            {{--<div class="col-md-2 col-xs-4" style="margin-top: 15px">--}}
                {{--<h5>Achieve Sampling Target Hits:</h5>--}}
                {{--<p class="text-primary">{{ $location->hits()->count() > $location->target_hits ? $location->target_hits : $location->hits()->count() }} / {{ $location->target_hits }}</p>--}}
            {{--</div>--}}
            <div class="col-md-12"><hr></div>

            <div class="col-md-12">
                <h2>ACTIVITY DETAIL REPORT:</h2>
                <table class="table table-bordered">
                    <thead>
                    <tr class="tableheader" style="background-color: #FF7300; color: white;">
                        <th>Date</th>
                        <th>User</th>
                        <th>Date and Time</th>
                        <th>Location</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($locations as $gps)
                        <tr>
                            <td>{{ $gps->created_at->toFormattedDateString() }}</td>
                            <td>{{ $gps->user->profile->full_name }}</td>
                            <td>{{ $gps->created_at }}</td>
                            <td>{{ $gps->formatted_address }}</td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        $(function() {
            $('.clickable').on('click', function() {
                location.href = $(this).data('uri');
            });
        });
    </script>
</body>
</html>
