@extends('admin.app')
@section('dependencies')
<link rel="stylesheet" href="{{ asset('/assets/asset/css/plugins/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/asset/css/plugins/responsive.bootstrap5.min.css') }}">
@endsection
@section('content')

  <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
          <div class="page-block">
            <div class="row align-items-center">
              <div class="col-md-12">
                <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                  <li class="breadcrumb-item"><a href="#">User</a></li>
                  <li class="breadcrumb-item" aria-current="page">Users</li>
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
          <div class="col-sm-12">
            <div class="card">
              <div class="card-header d-flex justify-content-between">
                <h3>Data Users</h3>
                <button class="btn btn-shadow btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#create">Tambah User</button>
              </div>
              <div class="card-body">
                <div class="dt-responsive table-responsive">
                  <table id="new-cons" class="display table table-striped table-hover dt-responsive nowrap" style="width: 100%">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama depan</th>
                        <th>Nama belakang</th>
                        <th>E-mail</th>
                        <th>Level</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $datauser)
                        @php

                            $parts = explode(' ', $datauser['full_name']);
                            $first = array_shift($parts);
                            $last = implode(' ', $parts);
                        @endphp
                      <tr>
                        <td>{{ $index +=1 }}</td>
                        <td>{{ $first}}</td>
                        <td>{{ $last }}</td>
                        <td>{{ $datauser['email'] }}</td>
                        <td></td>
                        <td></td>
                      </tr>
                       @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- `New` Constructor table end -->
        </div>
        <!-- [ Main Content ] end -->
      </div>
@endsection

@section('modal-create')
<div id="create" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('user.store') }}" method="post" >

            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Nama User</label>
                <input type="email" class="form-control" id="name" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email User</label>
                <input type="email" class="form-control" id="email" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" id="password">
            </div>

            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Level User</label>
                <select class="form-select" aria-label="Default select example">
                    <option selected disabled>--Pilih Level--</option>
                    @foreach ($level as $keylevel)
                    <option value="{{ $keylevel->id }}">{{ $keylevel->level_name }}</option>
                    @endforeach
                </select>
            </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create">Launch demo modal</button>
@endsection

@section('js')
 <!-- datatable Js -->
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js') }}"></script>
    <script src="{{ asset('/assets/asset/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/asset/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('/assets/asset/js/plugins/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/assets/asset/js/plugins/responsive.bootstrap5.min.js') }}"></script>
    <script>
      // [ Configuration Option ]
      $('#res-config').DataTable({
        responsive: true
      });

      // [ New Constructor ]
      var newcs = $('#new-cons').DataTable();

      new $.fn.dataTable.Responsive(newcs);

      // [ Immediately Show Hidden Details ]
      $('#show-hide-res').DataTable({
        responsive: {
          details: {
            display: $.fn.dataTable.Responsive.display.childRowImmediate,
            type: ''
          }
        }
      });
    </script>
@endsection
