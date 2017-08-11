<div class="modal fade" id="editLocation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <p>Edit Location</p>
            </div>
            <form action="/management/projects/update/{{$project['id']}}/locations/{{ $location['id'] }}/update" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    {{ csrf_field() }}


                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Location</label>
                                    <input type="text" class="form-control input-field" name="name" id="name"
                                           value="{{ isset($location['name']) ? $location['name'] : "" }}" placeholder="Location">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_type">Project Type (Sampling, Experiential)</label>
                                    <input type="text" class="form-control input-field" name="project_type" id="project_type"
                                           value="{{ isset($location['project_type']) ? $location['project_type'] : "" }}" placeholder="Project Type (Sampling, Experiential)">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="manual_hits">Manual Hits</label>
                                    <input type="text" class="form-control input-field" name="manual_hits" id="manual_hits"
                                           value="{{ isset($location['manual_hits']) ? $location['manual_hits'] : "" }}" placeholder="Manual Hits">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="target_hits">Target Hits</label>
                                    <input type="text" class="form-control input-field" name="target_hits" id="target_hits"
                                           value="{{ isset($location['target_hits']) ? $location['target_hits'] : "" }}" placeholder="Target Hits">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" class="form-control input-field" name="date" id="date"
                                           value="{{ isset($location['date']) ? $location['date'] : "" }}" placeholder="Date">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Status</label>
                                    <select name="status" id="status" class="form-control input-field">
                                        @if($location['status'] == 'pending')
                                            <option value="pending">Pending</option>
                                            <option value="on-going">On-Going</option>
                                            <option value="completed">Completed</option>
                                        @elseif($location['status'] == 'on-going')
                                            <option value="on-going">On-Going</option>
                                            <option value="pending">Pending</option>
                                            <option value="completed">Completed</option>
                                        @elseif($location['status'] == 'completed')
                                            <option value="completed">Completed</option>
                                            <option value="on-going">On-Going</option>
                                            <option value="pending">Pending</option>
                                        @endif

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success importBtn">Save Location</button>
                </div>
            </form>
        </div>
    </div>
</div>