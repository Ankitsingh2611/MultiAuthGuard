@extends('dashboard.user.layout')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-3">
                <h2>Registration Page User</h2>
                @if(Session::has('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                @endif
                @if(Session::has('error'))
                    <div class="alert alert-danger">
                        {{ Session::get('error') }}
                    </div>
                @endif
                <form action="{{ route('user.create') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter your first name" value="{{ old('first_name') }}" >
                        <span class="text-danger">@error('first_name'){{ $message }}@enderror</span>
                      </div>
                      <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter your last name" value="{{ old('last_name') }}" >
                        <span class="text-danger">@error('last_name'){{ $message }}@enderror</span>
                      </div>

                      <div class="mb-3">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo" >
                        <span class="text-danger">@error('photo'){{ $message }}@enderror</span>
                      </div>
                      <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter your mobile" value="{{ old('mobile') }}" maxlength="10" >
                        <span class="text-danger">@error('mobile'){{ $message }}@enderror</span>
                      </div>
                    <div class="mb-3">
                      <label for="email" class="form-label">Email address</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" value="{{ old('email') }}">
                      <span class="text-danger">@error('email'){{ $message }}@enderror</span>
                    </div>
                    <div class="mb-3">
                      <label for="password" class="form-label">Password</label>
                      <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
                      <span class="text-danger">@error('password'){{ $message }}@enderror</span>
                    </div>
                    <div class="mb-3">
                      <label for="cpassword" class="form-label">Confirm Password</label>
                      <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Enter your confirm password">
                      <span class="text-danger">@error('cpassword'){{ $message }}@enderror</span>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <input type="radio" name="gender" value="male" checked="checked"> Male
                        <input type="radio" name="gender" value="female"> Female
                        <input type="radio" name="gender" value="other"> Other
                    </div>
                    <div class="mb-3">
                        <label for="country-list" class="form-label">Country</label>
                        <select  id="country-list" name="country_list" class="form-control">
                            <option value="">Select Country</option>
                            @foreach ($countries as $data)
                            <option value="{{$data->country_id}}">
                                {{$data->country_name}}
                            </option>
                            @endforeach
                        </select>
                        <span class="text-danger">@error('country_list'){{ $message }}@enderror</span>
                    </div>
                    <div class="mb-3">
                        <label for="states-list" class="form-label">State</label>
                        <select id="states-list" name="states_list" class="form-control">
                            <option value="">Select State</option>
                        </select>
                        <span class="text-danger">@error('states_list'){{ $message }}@enderror</span>
                    </div>
                    <div class="mb-3">
                        <label for="cities-list" class="form-label">City</label>
                        <select id="cities-list" name="cities_list" class="form-control">
                            <option value="">Select City</option>
                        </select>
                        <span class="text-danger">@error('cities_list'){{ $message }}@enderror</span>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" rows="4" cols="50"></textarea>
                        <span class="text-danger">@error('address'){{ $message }}@enderror</span>
                    </div>
                    <div class="mb-3">
                        <label for="pincode" class="form-label">Pincode</label>
                        <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Enter your pincode" value="{{ old('pincode') }}" maxlength="6">
                        <span class="text-danger">@error('pincode'){{ $message }}@enderror</span>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Hobby</label><br/>
                        <input type="checkbox" name="hobby[]" value="Cricket" > Cricket
                        <input type="checkbox" name="hobby[]" value="Football"> Football
                        <input type="checkbox" name="hobby[]" value="Reading"> Reading
                    </div>
                    <div class="mb-3">
                        <div class="captcha">
                            <span>{!! captcha_img() !!}</span>
                            <button type="button" class="btn btn-success btn-refresh"><i class="fa fa-refresh"></i></button>
                            </div>
                            <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha">
                            @if ($errors->has('captcha'))
                                <span class="text-danger">
                                    <strong>{{ $errors->first('captcha') }}</strong>
                                </span>
                            @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    Already Register <a href="{{ route('user.login') }}">Login here.</a>
                  </form>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#country-list').on('change', function () {
                var idCountry = this.value;
                $("#states-list").html('');
                $.ajax({
                    url: "{{url('get-states')}}",
                    type: "POST",
                    data: {
                        country_id: idCountry,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('#states-list').html('<option value="">Select State</option>');
                        $.each(result.states, function (key, value) {
                            $("#states-list").append('<option value="' + value.state_id + '">' + value.state_name + '</option>');
                        });
                        $('#cities-list').html('<option value="">Select City</option>');
                    }
                });
            });
            $('#states-list').on('change', function () {
                var stateId = this.value;
                $("#cities-list").html('');
                $.ajax({
                    url: "{{url('get-cities')}}",
                    type: "POST",
                    data: {
                        state_id: stateId,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (stateresult) {
                        $('#cities-list').html('<option value="">Select City</option>');
                        $.each(stateresult.cities, function (key, value) {
                            $("#cities-list").append('<option value="' + value.city_id + '">' + value.city_name + '</option>');
                        });
                    }
                });
            });
        });
    </script>
    <script type="text/javascript">
        $(".btn-refresh").click(function(){
          $.ajax({
             type:'GET',
             url:'refresh_captcha',
             success:function(data){
                $(".captcha span").html(data.captcha);
             }
          });
        });
        </script>
@endsection
