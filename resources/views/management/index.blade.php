@extends('layouts.management')

@section('styles')
    <style>
        #statistics {
            margin: 0;
            padding: 0;
        }

        #statistics .item {
            min-height: 200px;
            padding-top: 30px;
            text-align: center;
        }

        #statistics .item .title {
            font-size: 20px;
            color: #fff;
        }

        #statistics .item .content {
            font-size: 100px;
            color: #fff;
            line-height: 80px;
        }

        .bg-success {
            background-color: #FF7300 !important;
        }

        .bg-info {
            background-color: #FF9D4C;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1 class="agency-title">Activations Advertising Inc</h1>
                        <h4>Monitor all the data from consumer engagement activities</h4>

                        <hr>

                        <!-- Some statistics -->
                        
                        <div class="row" id="statistics">
                            <!-- Active Projects -->
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <div class="col-xs-12 col-sm-12 col-md-12 item bg-primary">
                                    <div class="title">Active Projects</div>
                                    <div class="content">10</div>
                                </div>
                            </div>

                            <!-- Active Locations -->
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <div class="col-xs-12 col-sm-12 col-md-12 item bg-success">
                                    <div class="title">Active Locations</div>
                                    <div class="content">10</div>
                                </div>
                            </div>
                            <!-- Livestreaming Count -->
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <div class="col-xs-12 col-sm-12 col-md-12 item bg-info">
                                    <div class="title">Active Livestream</div>
                                    <div class="content">10</div>
                                </div>
                            </div>
                            <!-- Number of clients -->
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <div class="col-xs-12 col-sm-12 col-md-12 item bg-info">
                                    <div class="title"># of Clients</div>
                                    <div class="content">10</div>
                                </div>
                            </div>
                        </div>


                        <!-- Some listing of maybe top 10 of something -->

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection