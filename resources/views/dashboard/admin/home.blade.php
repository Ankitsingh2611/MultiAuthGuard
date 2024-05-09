@extends('dashboard.admin.layout')
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
                <p>Name : {{ Auth::guard('admin')->user()->name }}</p>
                <p>Email : {{ Auth::guard('admin')->user()->email }}</p>
                <p>
                    <form action="{{ route('admin.logout') }}" method="POST" >
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </p>
            </div>
        </div>
    </div>

@endsection
