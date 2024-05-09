@extends('dashboard.user.layout')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-3">
                <h2>Login Page User</h2>
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
                <form action="{{ route('user.dologin') }}" method="POST">
                    @csrf
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
                    <button type="submit" class="btn btn-primary">Submit</button><br/>
                    New User <a href="{{ route('user.register') }}">Register here.</a><br/>
                    <a href="{{ route('user.forgot_password') }}">Forgot Password</a>
                  </form>
            </div>
        </div>
    </div>

@endsection
