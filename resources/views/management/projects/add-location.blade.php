<div class="modal fade" id="addLocation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <p>Add Location</p>
            </div>
            <form action="/management/projects/update/{{$project->id}}/locations" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    {{ csrf_field() }}


                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Location</label>
                                    <input type="text" class="form-control input-field" name="name" id="name" placeholder="Location"
                                           value='{{ isset( $project->locations[0]->name ) ? $project->locations[0]->name : "" }}'>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="target_hits">Target Hits</label>
                                    <input type="text" class="form-control input-field" name="target_hits" id="target_hits" placeholder="Target Hits"
                                           value='{{ isset( $project->locations[0]->target_hits ) ? $project->locations[0]->target_hits : "" }}'>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" class="form-control input-field" name="date" id="date" placeholder="Date"
                                           value='{{ isset( $project->locations[0]->date ) ? $project->locations[0]->date : "" }}'>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="bas">Brand Ambassadors</label>
                                    <select id="bas" name="bas[]" class="form-control">
                                        <option value="0" selected disabled>Select brand ambassadors</option>
                                        {{--<option value="0">Select brand ambassadors</option>--}}
                                        {{--<option value="0">Select brand ambassadors</option>--}}
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="assigned_raspberry">Assigned VBox (For livestreaming)</label>
                                    <select class="form-control input-field" id="assigned_raspberry" name="assigned_raspberries[]" id="assigned_raspberry" >
                                        <option value="0" selected>No livestreaming</option>
                                        @for($i = 1; $i <= 25; $i++)
                                            <option value="raspi-{{$i}}">V-BOX {{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="video_name">Video Name</label>
                                    <input type="text" class="form-control input-field" id="video_name" name="video_names[]" placeholder="Video Name"
                                           value='{{ isset( $project->locations[0]->video_name ) ? $project->locations[0]->video_name : "" }}'>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="test">&nbsp;</label>
                                    <button type="button"
                                            class="btn btn-default btn-block add-video">
                                        <i class="glyphicon glyphicon-plus"></i> Add Video
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Services</label> <br>
                                    <label><input type="checkbox" value="manual" class="checkbox-input" name="locations[0][services][]"> Manual V-App</label>
                                    <label><input type="checkbox" value="automatic" class="checkbox-input" name="locations[0][services][]"> Automatic V-App</label>
                                    <label><input type="checkbox" value="gps" class="checkbox-input" name="locations[0][services][]"> GPS Tracker</label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success importBtn">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>