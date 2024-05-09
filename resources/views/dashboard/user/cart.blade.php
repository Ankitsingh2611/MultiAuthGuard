@extends('dashboard.user.layout')

@section('content')
    <div class="container">
        <div class="row">
                <div class="col-md-6 offset-3 mt-4" style="box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;">
                    <h2>Cart details</h2>
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Sl.No.</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                //print_r(session('cart'));
                                $i=1; $subtotal=0;
                            @endphp
                            @foreach (session('cart') as $id => $product )
                                <tr ProductId={{ $id }}>
                                    <td>{{ $i }}</td>
                                    <td>{{ $product['name'] }}</td>
                                    <td>{{ $product['category'] }}</td>
                                    <td style="text-align: center;">{{ $product['quantity'] }}</td>
                                    <td>{{ $product['price']*$product['quantity'] }}</td>
                                    <td><a class="product_delete" style="cursor: pointer;"><i class="fa fa-trash"></i></a></td>
                                </tr>
                                 @php
                                    $i++;
                                    $subtotal = $subtotal + $product['price']*$product['quantity'];
                                 @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th colspan="2" style="text-align: right;">Rs. {{ $subtotal }}</th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="container">
                        <div class="row">
                            <h2>Address details</h2>
                            <form method="post" action="{{ route('user.save.order') }}" class="mx-1 mx-md-4" enctype="multipart/form-data" >
                                @csrf
                                <div class="d-flex flex-row align-items-center mb-4">
                                  <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                  <div class="form-outline flex-fill mb-0">
                                    <input type="text" name="name" id="name" value="{{ $user->first_name.' '.$user->last_name }}" class="form-control" placeholder="enter your name" value="{{ old('name') }}" />
                                    <label class="form-label" for="name">Your Name</label>
                                    @if ($errors->has('name'))
                                        <div class="alert alert-danger" role="alert">{{ $errors->first('name') }}</div>
                                    @endif
                                  </div>
                                </div>

                                <div class="d-flex flex-row align-items-center mb-4">
                                    <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                    <div class="form-outline flex-fill mb-0">
                                      <input type="text" name="email" id="email" value="{{ $user->email }}" class="form-control" placeholder="enter your email" value="{{ old('email') }}" />
                                      <label class="form-label" for="email">Your email</label>
                                      @if ($errors->has('email'))
                                          <div class="alert alert-danger" role="alert">{{ $errors->first('email') }}</div>
                                      @endif
                                    </div>
                                  </div>

                                  <div class="d-flex flex-row align-items-center mb-4">
                                    <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                    <div class="form-outline flex-fill mb-0">
                                      <input type="text" name="mobile" id="mobile" value="{{ $user->mobile }}" class="form-control" placeholder="enter your mobile" maxlength="10" value="{{ old('mobile') }}" />
                                      <label class="form-label" for="mobile">Your mobile</label>
                                      @if ($errors->has('mobile'))
                                          <div class="alert alert-danger" role="alert">{{ $errors->first('mobile') }}</div>
                                      @endif
                                    </div>
                                  </div>


                                  <div class="d-flex flex-row align-items-center mb-4">
                                      <i class="fas fa-people fa-lg me-3 fa-fw"></i>
                                      <div class="form-outline flex-fill mb-0">
                                        <textarea name="address" id="address" class="form-control" >{{ $user->address }}</textarea>
                                        <label class="form-label" for="address">Address</label>
                                        @error('address')
                                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                        @enderror
                                      </div>
                                    </div>










                                <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                  <input type="submit" class="btn btn-primary btn-lg" name="submit" value="Proced to Payment" />
                                </div>

                              </form>
                        </div>
                        <!-- <a href="{{ url('order') }}" class="btn btn-success" >Complete Order</a> -->
                    </div>
                </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            //alert('hello');
            $('.product_delete').on('click',function(e) {
                //alert('product delete click');
                e.preventDefault();
                var element = $(this);
                //alert(element.parents("tr").attr('ProductId'));
                //alert(element);
                $.ajax({
                    url:"{{ url('delete-cart-product') }}",
                    type:"POST",
                    data:{
                        _token:'{{ csrf_token() }}',
                        id: element.parents("tr").attr('ProductId')
                    },
                    success: function (retrunDeleteResult) {
                        window.location.reload();
                    }
                });
            });

        });
      </script>

@endsection
