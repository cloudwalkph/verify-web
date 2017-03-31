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
            <div class="image-container">
                <img src="{{ asset('images/verify_white.png') }}" alt="logo" style="height: 100px">
            </div>
            <div class="row">
                <div class="col-md-4 col-xs-12">
                    <h2>{{ $project->name }}</h2>
                    <p>{{ $location->name }}</p>
                </div>
                <div class="col-md-2 col-xs-6" style="margin-top: 15px">
                    <h5>Status:</h5>
                    <p class="text-primary">Completed</p>
                </div>
                <div class="col-md-2 col-xs-6" style="margin-top: 15px">
                    <h5>Last Updated:</h5>
                    <p class="text-primary">{{ $project->updated_at->toFormattedDateString() }}</p>
                </div>
                <div class="col-md-2 col-xs-6" style="margin-top: 15px">
                    <h5>Runs Completed:</h5>
                    <p class="text-primary">500 of 1000 (50%)</p>
                </div>
                <div class="col-md-2 col-xs-6" style="margin-top: 15px">
                    <h5>Achieved Target Hits:</h5>
                    <p class="text-primary">500,000 of 1,000,000 (50%)</p>
                </div>
                <div class="col-md-12 col-xs-12"><hr></div>

                <div class="col-md-12 col-xs-12">
                    <h1>Audit Report</h1>
                    <p>Lorem Khaled Ipsum is a major key to success. Stay focused. I’m up to something. Look at the sunset, life is amazing, life is beautiful, </p>
                </div>
            </div>

            <div class="row">
                @foreach($hits as $hit)
                    <div class="col-md-6 col-xs-6" style="margin: 50px 0">
                        <div class="col-md-6 col-xs-6 text-center">
                            <img src="{{ asset('images/1.jpg') }}" height="100" width="100" class="img-circle" alt="">
                        </div>
                        <div class="col-md-6 col-xs-6">
                            <h4>{{ $hit->name }}</h4>
                            <p>{{ $hit->email }}</p>
                            <p>{{ $hit->contact_number }}</p>
                            <p>{{ $hit->created_at->toFormattedDateString() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
