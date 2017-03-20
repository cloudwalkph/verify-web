@extends('layouts.app')

@section('content')
<div class="alert alert-primary" style="text-align: center">
    <img src="{{ asset('images/ic_sms_failed_24px.png') }}" alt="info"> CLICK ON A PROJECT TO VIEW MORE DETAILS
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Project Name</th>
                                <th>Active Runs</th>
                                <th>Achieved Target Hits</th>
                                <th>Deployed Runs</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    <strong>Creamsilk Hair Dare</strong>
                                </td>

                                <td>
                                    0 / 100
                                </td>

                                <td>
                                    2500 / 5000
                                </td>

                                <td>
                                    500 / 1000
                                </td>

                                <td>
                                    Active
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <strong>Creamsilk Hair Dare</strong>
                                </td>

                                <td>
                                    0 / 100
                                </td>

                                <td>
                                    2500 / 5000
                                </td>

                                <td>
                                    500 / 1000
                                </td>

                                <td>
                                    Active
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <strong>Creamsilk Hair Dare</strong>
                                </td>

                                <td>
                                    0 / 100
                                </td>

                                <td>
                                    2500 / 5000
                                </td>

                                <td>
                                    500 / 1000
                                </td>

                                <td>
                                    Active
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
