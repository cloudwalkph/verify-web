@extends('layouts.auth')

@section('content')
<div class="flex">
    <img src="{{ asset('images/verify.png') }}" class="logo-big img-responsive" alt="logo-big">

    <h5 class="text-center">Collect, track, and record accurate and reliable data from consumer engagements. </h5>
    <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

            <div class="col-md-12">
                <input id="email" type="email" class="form-control" name="email"
                       placeholder="Type your email address"
                       value="{{ old('email') }}" required autofocus>

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <div class="col-md-12">
                <input id="password" type="password" class="form-control"
                       placeholder="Type your password"
                       name="password" required>

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
                <button type="submit" class="btn btn-primary btn-block">
                    Sign In
                </button>
            </div>
        </div>
    </form>
</div>

<h5 class="text-center">Copyright © 2017 Cloudwalk Digital Inc. All rights reserved.</h5>
@endsection
