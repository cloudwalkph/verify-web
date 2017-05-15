@extends('layouts.app') 

@section('content')

    <div class="info-section">
        <div class="info-title">
            <h2 style="color: #fff">
                {{ Auth::user()->profile->full_name }}
            </h2>
        </div>
        <a href="/management" class="btn btn-primary" style="width: 200px">View Dashboard</a>
    </div>
    <div class="info-section">
        <div class="nav-tabs-custom">
            <ul class="nav custom-tabs">
                <li class="active"><a href="#profile" data-toggle="tab">My Profile</a></li>
                <li><a href="#brands" data-toggle="tab">Brands</a></li>
                <li><a href="#accounts" data-toggle="tab">Accounts</a></li>
                <li><a href="#userGroups" data-toggle="tab">User Groups</a></li>
            </ul>
        </div>
    </div>
    <div class="alert alert-primary" style="text-align: center">
        <img src="{{ asset('images/ic_sms_failed_24px.png') }}" alt="info"> CLICK ON THE TABLE TO EDIT DATA
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="tab-content">
                            @include('management.admin.tabs.profile')
                            @include('management.admin.tabs.brands')
                            @include('management.admin.tabs.accounts')
                            @include('management.admin.tabs.groups')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection