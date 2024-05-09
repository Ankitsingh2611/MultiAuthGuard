@extends('dashboard.user.layout')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Order Details</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Order details List
                </h6>
                <p>Order Id : {{ $data->orders_id }}</p>
                <p>Name : {{ $data->name }}</p>
                <p>Email : {{ $data->email }}</p>
                <p>Payment ID : {{ $data->payment_id }}</p>
                <p>Payment Status : {{ $data->payment_status }}</p>
                <p>Payment Date : {{ $data->payment_date }}</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Order Id</th>
                                <th>product_name</th>
                                <th>price</th>
                                <th>quantity</th>
                                <th>sub_amount</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Id</th>
                                <th>Order Id</th>
                                <th>product_name</th>
                                <th>price</th>
                                <th>quantity</th>
                                <th>sub_amount</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($order_details as $order )
                                <tr>
                                    <td>{{ $order->orders_details_id }}</td>
                                    <td>{{ $order->orders_id }}</td>
                                    <td>{{ $order->product_name }}</td>
                                    <td>{{ $order->price }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>{{ $order->sub_amount }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection
