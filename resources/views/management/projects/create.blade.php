@extends('layouts.management')

@section('scripts')
    <script>
        $(function() {
            $('#user_id').selectize({
                placeholder: 'Select a client user',
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                create: false,
                render: {
                    option: function(item, escape) {
                        return '<div>' +
                                '<span class="title">' +
                                    '<span>' + escape(item.name) + '</span>' +
                                '</span>' +
                            '</div>';
                    }
                },
                load: function(query, callback) {
                    if (!query.length) return callback();

                    let url = '/management/users/json?group=2&q=' + encodeURIComponent(query);

                    axios.get(url).then((response) => {
                        callback(response.data);
                    }).catch((err) => {
                        callback();
                    });

                }
            });
        });
    </script>
@endsection

@section('content')

    <div class="info-section">
        <div class="info-title">
            <a href="/management/projects" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
            <h2 style="color: #fff">
                Add New Project
            </h2>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="content">
                            <div class="row">
                                <div class="col-md-12">
                                    <h1>Project Details</h1>
                                </div>
                            </div>

                           <form action="/management/projects/create" method="POST">
                                {{ csrf_field() }}
                                @include('management.projects.form')
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection