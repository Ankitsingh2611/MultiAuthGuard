@extends('dashboard.admin.layout')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Add Product</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Enter Product Details
                    <a href="{{ url('admin/products') }}" class="btn btn-danger" style="float: right" >Return to Products list</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">

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

                    <div class="col-md-6 offset-3">


                    <form method="post" action="{{ url('admin/save-product') }}" class="mx-1 mx-md-4" enctype="multipart/form-data" >
                        @csrf
                        <div class="d-flex flex-row align-items-center mb-4">
                          <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                          <div class="form-outline flex-fill mb-0">
                            <input type="text" name="name" id="name" class="form-control" placeholder="enter product name" value="{{ old('name') }}" />
                            <label class="form-label" for="name">Product Name</label>
                            @if ($errors->has('name'))
                                <div class="alert alert-danger" role="alert">{{ $errors->first('name') }}</div>
                            @endif
                          </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                          <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                          <div class="form-outline flex-fill mb-0">
                            <input type="text" name="category" id="category" class="form-control" placeholder="enter product category" value="{{ old('category') }}" />
                            <label class="form-label" for="category">Product Category</label>
                            @error('category')
                                <div class="alert alert-danger" role="alert">{{ $message }}</div>
                            @enderror
                          </div>
                        </div>
                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-people fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0">
                              <textarea name="description" id="description" class="form-control" ></textarea>
                              <label class="form-label" for="description">description</label>
                              @error('description')
                                  <div class="alert alert-danger" role="alert">{{ $message }}</div>
                              @enderror
                            </div>
                          </div>



                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-people fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0">
                              <input type="file" name="photo" id="photo" class="form-control" />
                              <label class="form-label" for="photo">Product Photo</label>
                              @error('photo')
                                  <div class="alert alert-danger" role="alert">{{ $message }}</div>
                              @enderror
                            </div>
                          </div>

                          <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0">
                              <input type="text" name="price" id="price" class="form-control" placeholder="enter product price" value="{{ old('price') }}" />
                              <label class="form-label" for="price">Product price</label>
                              @error('price')
                                  <div class="alert alert-danger" role="alert">{{ $message }}</div>
                              @enderror
                            </div>
                          </div>
                          <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0">
                              <input type="text" name="seller" id="seller" class="form-control" placeholder="enter product seller name" value="{{ old('seller') }}" />
                              <label class="form-label" for="seller">Product seller</label>
                              @error('seller')
                                  <div class="alert alert-danger" role="alert">{{ $message }}</div>
                              @enderror
                            </div>
                          </div>

                        <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                          <input type="submit" class="btn btn-primary btn-lg" name="submit" value="Submit" />
                        </div>

                      </form>
                    </div>



                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection
