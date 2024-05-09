@extends('dashboard.admin.layout')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-3">
                <h2>Admin Change Password Page</h2>
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
                <form action="{{ route('admin.password-update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                      <label for="current_password" class="form-label">Current Password</label>
                      <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter your Current Password" value="">
                      <span class="text-danger">@error('current_password'){{ $message }}@enderror</span>
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
                    <button type="submit" class="btn btn-primary">Update Password</button>
                  </form>
            </div>
        </div>
    </div>

@endsection
