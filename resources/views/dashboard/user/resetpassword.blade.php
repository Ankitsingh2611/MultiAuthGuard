@extends('dashboard.user.layout')

@section('content')
<section class="vh-100" style="background-color: #eee;">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-12 col-xl-11">
          <div class="card text-black" style="border-radius: 25px;">
            <div class="card-body p-md-5">
              <div class="row justify-content-center">
                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                  <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Reset Password</p>
                  @if (Session::has('success'))
                        <div class="alert alert-success">{{ Session::get('success') }}</div>
                  @endif
                  <form method="post" action="{{ url('reset-password') }}" class="mx-1 mx-md-4" enctype="multipart/form-data" >
                    @csrf
                    <input type="hidden" name="token" id="token" class="form-control"  value="{{ $token }}" />
                    <div class="d-flex flex-row align-items-center mb-4">
                        <i class="fa fa-envelope fa-lg me-3 fa-fw"></i>
                        <div class="form-outline flex-fill mb-0">
                          <input type="email" name="email" id="email" class="form-control" placeholder="enter your email" value="{{ old('email') }}" />
                          <label class="form-label" for="email">Your Email</label>
                          @error('email')
                              <div class="alert alert-danger" role="alert">{{ $message }}</div>
                          @enderror
                        </div>
                      </div>
                    <div class="d-flex flex-row align-items-center mb-4">
                      <i class="fa fa-lock fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <input type="password" name="password" id="password" class="form-control" value="{{ old('password') }}" />
                        <label class="form-label" for="password">New Password</label>
                        @error('password')
                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>

                    <div class="d-flex flex-row align-items-center mb-4">
                      <i class="fa fa-key fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" value="{{ old('confirm_password') }}" />
                        <label class="form-label" for="confirm_password">Repeat your Password</label>
                        @error('confirm_password')
                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>


                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                      <input type="submit" class="btn btn-primary btn-lg" name="submit" value="Update" />
                    </div>

                  </form>

                </div>
                <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                  <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-registration/draw1.webp"
                    class="img-fluid" alt="Sample image">

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection
