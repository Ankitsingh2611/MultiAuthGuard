@extends('dashboard.admin.layout')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-3">
            <h2>Edit Page admin</h2>
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
            <form action="{{ route('admin.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" value="{{ Auth::guard('admin')->user()->name }}" >
                    <span class="text-danger">@error('name'){{ $message }}@enderror</span>
                  </div>

                  <div class="mb-3">
                    <label for="mobile" class="form-label">Mobile</label>
                    <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter your mobile" value="{{ Auth::guard('admin')->user()->mobile }}" maxlength="10" >
                    <span class="text-danger">@error('mobile'){{ $message }}@enderror</span>
                  </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
              </form>
        </div>
    </div>
</div>
@endsection
