@extends('layouts.management')

@section('styles')
    <link rel="stylesheet" href="/dropzone/basic.css">
    <link rel="stylesheet" href="/dropzone/dropzone.css">
@endsection

@section('content')
    <div class="info-section">
        <div class="info-title">
            <div class="col-sm-7" style="display: inline-flex;">
                <a href="/management/projects/update/{{ $project['id'] }}" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
                <h1 style="color: #fff">
                    {{ $project->name }}
                    <p class="info-sub-title">{{ $location->name }}</p>
                </h1>
            </div>
            <div class="col-sm-3">
                <h5 style="color: #B4B4B4;"><b>Reported Hits:</b></h5>
                <h5 class="text-primary">{{ $location->manual_hits }}</h5>
            </div>
        </div>

        <div class="info-body">
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Location Actions <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#"
                           data-toggle="modal"
                           data-target="#editLocation">Update Location Information</a></li>

                    <li role="separator" class="divider"></li>

                    <li><a href="#" data-toggle="modal" data-target="#manageVbox">Manage Vboxes</a></li>
                    <li><a href="#" data-toggle="modal" data-target="#manageTeam">Manage Team</a></li>
                </ul>
            </div>
        </div>

        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

        @if ($services && in_array('automatic', $services))
            <div class="info-body">
                <a href="#"
                   data-toggle="modal"
                   data-target="#uploadImages"
                   class="btn btn-primary">Upload Images Manually</a>
            </div>

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <div class="info-body">
                <a href="#"
                   data-toggle="modal"
                   data-target="#uploadVideos"
                   class="btn btn-primary">Upload Videos</a>
            </div>

            <div class="modal fade" id="uploadImages" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" style="width: 80%" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Upload Face Images</h4>
                        </div>
                        <div class="modal-body">
                            <form class="dropzone" id="myDropzone" action="/projects/{{ $project->id }}/locations/{{ $location->id }}/faces" method="POST">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="uploadVideos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" style="width: 80%" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Upload Video Recordings</h4>
                        </div>
                        <div class="modal-body">
                            <form class="dropzone" id="myDropzoneVideos" action="/projects/{{ $project->id }}/locations/{{ $location->id }}/videos" method="POST">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @endif

        <div class="info-body">
            <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/event-reports"
               class="btn btn-primary">Verify Audit Report</a>
        </div>
    </div>

    @component('components.location-description',
        ['project' => $project, 'location' => $location])
    @endcomponent

    <div class="container-fluid" style="margin-top: -130px">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @component('components.chart', ['project' => $project, 'location' => $location])
                            @slot('nav')
                                <li class="active">
                                    <a href="/management/projects/update/{{ $project['id'] }}/locations/{{ $location['id'] }}">Manual</a>
                                </li>

                                <li class="{{ $services && in_array('automatic', $services) ? '' : 'hide' }}">
                                    <a href="/management/projects/update/{{ $project['id'] }}/locations/{{ $location['id'] }}/automated">Automated</a>
                                </li>

                                <li class="{{ $services && in_array('gps', $services) ? '' : 'hide' }}">
                                    <a href="/management/projects/update/{{ $project['id'] }}/locations/{{ $location['id'] }}/gps">GPS</a>
                                </li>

                                <li class="{{ count($videos) <= 0 ? 'hide' : '' }}">
                                    <a href="/management/projects/update/{{ $project['id'] }}/locations/{{ $location['id'] }}/videos">Video
                                    </a>
                                </li>
                            @endslot
                            @slot('title')
                            @endslot
                            @slot('ongoingReport')
                            @endslot
                            @slot('timeandvideo')
                            @endslot
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('management.projects.locations.modals.manage-team')
    @include('management.projects.locations.modals.manage-vbox')
    @include('management.projects.locations.modals.upload-gps')
    @include('management.projects.locations.modals.edit-location')
@endsection

@section('scripts')
    <script src="/dropzone/dropzone.js"></script>

    <script>
        $(function () {
            $('.btn-manage-team').on('click', function() {
                let userId = $(this).data('user');
                $('#userId').val(userId);
            });

            // Add Multiple videos
            $(document).on('click', '.add-video', function() {
                let $el = $(this).parent()
                    .parent()
                    .parent()
                    .clone();

                $el.find('.input-field').val('');

                let $parent = $(this).parent()
                    .parent()
                    .parent()
                    .parent();

                $parent.append($el);

                $(this).html('<i class="glyphicon glyphicon-remove"></i>')
                    .removeClass('btn-default')
                    .removeClass('add-video')
                    .addClass('btn-danger')
                    .addClass('remove-video');
            });

            // Remove video
            $(document).on('click', '.remove-video', function() {
                $(this).parent()
                    .parent()
                    .parent()
                    .remove();
            });
        })
    </script>

    <script>
        Dropzone.options.myDropzone = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 2, // MB
            acceptedFiles: 'image/*',
            accept: function(file, done) {
                if (file.name == "justinbieber.jpg") {
                    done("Naha, you don't.");
                }

                else { done(); }
            },
            init: function() {
                this.on('sending', function(file, xhr, formData) {
                    formData.append('_token', window.Laravel.csrfToken);
                })
            }
        };

        Dropzone.options.myDropzoneVideos = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 3000, // MB
            acceptedFiles: 'video/*, .mkv',
            timeout: 600000,
            accept: function(file, done) {
                if (file.name == "justinbieber.jpg") {
                    done("Naha, you don't.");
                }

                else { done(); }
            },
            init: function() {
                this.on('sending', function(file, xhr, formData) {
                    formData.append('_token', window.Laravel.csrfToken);
                })
            }
        };

        $('#bas').selectize({
            placeholder: 'Select brand ambassadors',
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            plugins: ['remove_button'],
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

                let url = '/management/users/json?group=3&q=' + encodeURIComponent(query);

                axios.get(url).then((response) => {
                    callback(response.data);
                }).catch((err) => {
                    callback();
                });

            }
        });

        let $bas = $('#bas').selectize();
        let selectize = $bas[0].selectize;
        @foreach ($location->users as $user)
            selectize.addOption({ id: '{{ $user->id }}', name: '{{ $user->profile->full_name }}' });
            selectize.addItem('{{ $user->id }}');
            {{--$bas.addItem({{ json_encode(['id' => $user->id, 'name' => $user->profile->full_name ]) }});--}}
        @endforeach
    </script>
@endsection
