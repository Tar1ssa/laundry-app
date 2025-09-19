@extends('admin.app')
@section('content')
<div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
          <div class="page-block">
            <div class="row align-items-center">
              <div class="col-md-12">
                <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                  <li class="breadcrumb-item"><a href="#">Customer</a></li>
                  <li class="breadcrumb-item" aria-current="page">Edit Customer</li>
                </ul>
              </div>
              <div class="col-md-12">
                <div class="page-header-title">
                  <h2 class="mb-0">{{ $title }}</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <div class="row">
          <!-- [ form-element ] start -->
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3>Edit Customer</h3>
              </div>
              <div class="card-body">
                <form action="{{ route('customer.update', $edit->id)  }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-floating ">
                                        <input value="{{ $edit->name ? $edit->customer_name : old('name') }}" type="text" class="form-control" id="floatingName" placeholder="Nama" name="name">
                                        <label for="floatingName">Nama</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-floating ">
                                        <input value="{{ $edit->phone ? $edit->phone : old('phone') }}" type="number" class="form-control" id="floatingEmail" placeholder="Contoh: 083333XXXX" name="phone">
                                        <label for="floatingEmail">No.Telp</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="form-label" for="exampleFormControlTextarea1">Alamat</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="address">{{ $edit->address ? $edit->address : old('address') }}</textarea>
                                    </div>
                                </div>

                        </div>
                        <div class="col-md-6 d-flex justify-content-center align-items-center">
                            <i class="ti ti-user font-size-icon text-blue-500"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-shadow btn-primary">Submit</button>
                    <a href="{{ route('customer.index') }}" class="btn btn-shadow btn-secondary">Kembali</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
</div>
@endsection
