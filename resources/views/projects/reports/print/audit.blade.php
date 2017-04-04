<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <style>
        .animation-details th, .animation-details td {
            vertical-align: middle !important;
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


    <div class="col-md-12 col-xs-12 col-sm-12">
        <div class="content">
            <div class="image-container" style="margin-top: 50px">
                <img src="{{ asset('images/verify_white.png') }}" alt="logo" style="height: 100px">
            </div>
            <div class="row">
                <div class="col-md-4 col-xs-12">
                    <h2>{{ $project->name }}</h2>
                    <p>{{ $location->name }}</p>
                </div>
                <div class="col-md-2 col-xs-6" style="margin-top: 15px">
                    <h5>Status:</h5>
                    <p class="text-primary">{{ $location->status }}</p>
                </div>
                <div class="col-md-2 col-xs-6" style="margin-top: 15px">
                    <h5>Last Updated:</h5>
                    <p class="text-primary">{{ count($location->hits) > 0 ? $location->hits[count($location->hits) - 1]->created_at->toFormattedDateString() : 'N/A' }}</p>
                </div>

                <div class="col-md-2 col-xs-6" style="margin-top: 15px">
                    <h5>Achieved Target Hits:</h5>
                    <p class="text-primary">{{ $location->hits()->count() }} / {{ $location->target_hits }}</p>
                </div>
                <div class="col-md-12 col-xs-12"><hr></div>

                <div class="col-md-12 col-xs-12">
                    <h1>Audit Report</h1>
                    <p>Shows data and info from each individual hit.</p>
                </div>
            </div>

            <div class="col-md-12 col-xs-12">

                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th class="hide">Image</th>
                            <th>Name</th>
                            <th>Email Address</th>
                            <th>Contact Number</th>
                            <th>Age Group</th>
                            <th>Gender</th>
                        </tr>
                    @foreach($hits as $hit)
                        <tr>
                            <td class="hide"><img src="{{ asset('storage/'.$hit->image) }}" height="50" width="50" class="img-circle" alt=""></td>
                            <td>{{ $hit->name }}</td>
                            <td>{{ $hit->email }}</td>
                            <td>{{ $hit->contact_number }}</td>
                            @foreach($hit->answers as $answer)
                                <td>{{ $answer->value }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
