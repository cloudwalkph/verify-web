@extends('layouts.auth')

@section('content')
<div class="flex">
    <img src="{{ asset('images/logo-verify.png') }}" class="logo-big" alt="logo-big">
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
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block">
                    Login
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
