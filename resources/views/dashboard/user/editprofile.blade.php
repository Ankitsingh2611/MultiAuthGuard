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
            <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter your first name" value="{{ Auth::guard('web')->user()->first_name }}" >
                    <span class="text-danger">@error('first_name'){{ $message }}@enderror</span>
                  </div>
                  <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter your last name" value="{{ Auth::guard('web')->user()->last_name }}" >
                    <span class="text-danger">@error('last_name'){{ $message }}@enderror</span>
                  </div>

                  <div class="mb-3">
                    <label for="photo" class="form-label">Photo <img src="{{ asset('images/uploads/'.Auth::guard('web')->user()->photo) }}" alt="photo" height="50" /> </label>
                    <input type="file" class="form-control" id="photo" name="photo" >
                    <span class="text-danger">@error('photo'){{ $message }}@enderror</span>
                  </div>
                  <div class="mb-3">
                    <label for="mobile" class="form-label">Mobile</label>
                    <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter your mobile" value="{{ Auth::guard('web')->user()->mobile }}" maxlength="10" >
                    <span class="text-danger">@error('mobile'){{ $message }}@enderror</span>
                  </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email address</label>
                  <div><b>{{ Auth::guard('web')->user()->email }}</b></div>
                </div>
                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <input type="radio" name="gender" value="male" checked="checked"> Male
                    <input type="radio" name="gender" value="female"> Female
                    <input type="radio" name="gender" value="other"> Other
                </div>
                <div class="mb-3">
                    <label for="country-list" class="form-label">Country</label>
                    <select  id="country-list" name="country_list" class="form-control"  >
                        <option value="">Select Country</option>
                        @foreach ($countries as $data)
                            @if(Auth::guard('web')->user()->country == $data->country_id)
                                <option value="{{$data->country_id}}" selected>
                            @else
                                <option value="{{$data->country_id}}">
                            @endif
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
                        @foreach ($states as $data)
                            @if(Auth::guard('web')->user()->state == $data->state_id)
                                <option value="{{$data->state_id}}" selected>
                            @else
                                <option value="{{$data->state_id}}">
                            @endif
                                {{$data->state_name}}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-danger">@error('states_list'){{ $message }}@enderror</span>
                </div>
                <div class="mb-3">
                    <label for="cities-list" class="form-label">City</label>
                    <select id="cities-list" name="cities_list" class="form-control">
                        <option value="">Select City</option>
                        @foreach ($cities as $data)
                            @if(Auth::guard('web')->user()->city == $data->city_id)
                                <option value="{{$data->city_id}}" selected>
                            @else
                                <option value="{{$data->city_id}}">
                            @endif
                                {{$data->city_name}}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-danger">@error('cities_list'){{ $message }}@enderror</span>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea id="address" name="address" rows="4" cols="50">{{ Auth::guard('web')->user()->address }}</textarea>
                    <span class="text-danger">@error('address'){{ $message }}@enderror</span>
                </div>
                <div class="mb-3">
                    <label for="pincode" class="form-label">Pincode</label>
                    <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Enter your pincode" value="{{ Auth::guard('web')->user()->pincode }}" maxlength="6">
                    <span class="text-danger">@error('pincode'){{ $message }}@enderror</span>
                </div>
                <div class="mb-3">
                    @php
                        $hobbies = json_decode(Auth::guard('web')->user()->hobby);
                    @endphp
                    <label for="gender" class="form-label">Hobby</label><br/>
                    <input type="checkbox" name="hobby[]" value="Cricket" {{ in_array('Cricket',$hobbies)? 'checked':'' }} > Cricket
                    <input type="checkbox" name="hobby[]" value="Football" {{ in_array('Football',$hobbies)? 'checked':'' }} > Football
                    <input type="checkbox" name="hobby[]" value="Reading" {{ in_array('Reading',$hobbies)? 'checked':'' }} > Reading
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
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
@endsection
