@extends('dashboard.user.layout')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-3">
                <h2>Home Page</h2>
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
                <p>First Name : {{ Auth::guard('web')->user()->first_name }}</p>
                <p>Last Name : {{ Auth::guard('web')->user()->last_name }}</p>
                <p>Email : {{ Auth::guard('web')->user()->email }}</p>
                <p>Hobby :

                    @php
                        $hpbbyArray = json_decode(Auth::guard('web')->user()->hobby);
                    @endphp

                    @foreach ($hpbbyArray as $key => $jsons) {
                        {{  $jsons  }}
                   }
                    @endforeach

                </p>
                <p>
                    <form action="{{ route('user.logout') }}" method="POST" >
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </p>
                <p>
                    <a href="{{ route('user.editprofile') }}">Edit Profile</a>
                </p>
                <p>
                    <a href="{{ route('user.change_password') }}">Change Password</a>
                </p>
            </div>
        </div>
    </div>
@endsection
