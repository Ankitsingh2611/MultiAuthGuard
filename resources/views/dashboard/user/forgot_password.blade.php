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
                <form action="{{ route('user.forgotpwdpost') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                      <label for="email" class="form-label">Email address</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" value="{{ old('email') }}">
                      <span class="text-danger">@error('email'){{ $message }}@enderror</span>
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
