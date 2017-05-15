<div class="row">
    <div class="col-md-12">
        @include('components.errors')
        @include('components.success')
    </div>

    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label for="profile[first_name]">First Name</label>
                <input type="text" class="form-control" name="profile[first_name]" id="first_name" 
                    value='{{ isset( $user->profile['first_name'] ) ? $user->profile['first_name'] : "" }}' placeholder="First Name">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="profile[middle_name]">Middle Name</label>
                <input type="text" class="form-control" name="profile[middle_name]" id="middle_name" 
                    value='{{ isset( $user->profile['middle_name'] ) ? $user->profile['middle_name'] : "" }}' placeholder="Middle Name">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="profile[last_name]">Last Name</label>
                <input type="text" class="form-control" name="profile[last_name]" id="last_name" 
                    value='{{ isset( $user->profile['last_name'] ) ? $user->profile['last_name'] : "" }}' placeholder="Last Name">
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label for="profile[birthdate]">Birthdate</label>
                <input type="date" class="form-control" name="profile[birthdate]" id="birthdate" 
                    value='{{ isset( $user->profile['birthdate'] ) ? $user->profile['birthdate'] : "" }}' placeholder="Birthdate">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="profile[gender]">Gender</label>
                <select class="form-control" name="profile[gender]">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="user_group_id">User Group</label>
                <select class="form-control" name="user_group_id">
                    @foreach($groups as $g)
                        <option value="{{$g->id}}">{{ ucwords($g->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label for="profile[mobile_number]">Mobile Number</label>
                <input type="text" class="form-control" name="profile[mobile_number]" id="mobile_number" 
                    value='{{ isset( $user->profile['mobile_number'] ) ? $user->profile['mobile_number'] : "" }}' placeholder="Mobile Number">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="profile[landline]">Landline</label>
                <input type="text" class="form-control" name="profile[landline]" id="landline" 
                    value='{{ isset( $user->profile['landline'] ) ? $user->profile['landline'] : "" }}' placeholder="Landline">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" name="email" id="email" 
                    value='{{ isset( $user->email ) ? $user->email : "" }}' placeholder="Email Address">
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-12">
            <div class="form-group">
                <label for="profile[address]">Address</label>
                <textarea class="form-control" style="resize:none" name="profile[address]" rows="4" cols="4">{{ isset( $user->profile['address'] ) ? $user->profile['address'] : "" }}</textarea>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="New Password">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm New Password">
            </div>
        </div>
    </div>

    <div class="col-sm-12" style="text-align: right;">
        <a href="/management/profile" class="btn btn-danger" style="width: 200px">Cancel</a>
        <button type="submit" class="btn btn-success" style="width: 200px">Save</button>
    </div>

</div>