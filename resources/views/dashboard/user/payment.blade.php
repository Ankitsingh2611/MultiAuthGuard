@extends('dashboard.user.layout')
@section('content')
    <div class="container">
        <div class="row">
            <section class="p-4 p-md-5">
  <div class="row d-flex justify-content-center">
    <div class="col-md-10 col-lg-8 col-xl-5">
      <div class="card rounded-3">
        <div class="card-body p-4">
          <div class="text-center mb-4">
            <h3>Payment</h3>
          </div>
          <form action="{{ route('user.payment.save') }}" method="post" >
            @csrf
            <input type="hidden" id="payment_id" name="payment_id" value="{{ $payment_id }}">
            <div class="d-flex flex-row align-items-center mb-4 pb-1">
              <div class="flex-fill mx-3">
                <div class="form-outline">
                  <input type="text" name="card_number" id="formControlLgXs" class="form-control form-control-lg"
                    value=""  maxlength="19"/>
                  <label class="form-label" for="formControlLgXs">Card Number</label>
                </div>
              </div>
              <img class="img-fluid" src="https://img.icons8.com/color/48/000000/mastercard-logo.png" /><img class="img-fluid" src="https://img.icons8.com/color/48/000000/visa.png" />
            </div>

            <div class="row mb-4">
                <div class="col-6">
                  <div class="form-outline">
                    <input type="text" name="expire_date" id="formControlLgExpk" class="form-control form-control-lg"
                      placeholder="MM/YYYY" />
                    <label class="form-label" for="formControlLgExpk">Expire</label>
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-outline">
                    <input type="password" name="cvv" id="formControlLgcvv" class="form-control form-control-lg"
                      placeholder="CVV" />
                    <label class="form-label" for="formControlLgcvv">Cvv</label>
                  </div>
                </div>
              </div>
            <div class="form-outline mb-4">
              <input type="text" name="carholder_name" id="formControlLgXsd" class="form-control form-control-lg"
                value="" />
              <label class="form-label" for="formControlLgXsd">Cardholder's Name</label>
            </div>
            <div class="form-outline mb-4">
                Total Amount : <b>{{ $orders_details->total_amount }}</b>
            </div>


            <input style="float: right" type="submit" name="submit" class="btn btn-success btn-lg btn-block" value="Pay Now" >
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
        </div>
    </div>

@endsection
