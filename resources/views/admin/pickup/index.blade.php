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
                    <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                  <li class="breadcrumb-item" aria-current="page"><a href="#">Pickup Transaksi</a></li>
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
                <h3>Data Pickup Transaksi</h3>


              </div>
              <div class="card-body">
                <div class=" table-responsive">
                  <table id="new-cons" class="display table table-striped table-hover dt-responsive nowrap" style="width: 100%">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>No.Transaksi</th>
                        <th>Nama customer</th>
                        <th>Nama Pengambil</th>
                        <th>Tanggal transaksi</th>
                        <th>Tanggal pickup</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($Pickup as $index => $dataPickup)
                      <tr>
                        <td>{{ $index +=1 }}</td>
                        <td>{{ $dataPickup->order->order_code}}</td>
                        <td>{{ $dataPickup->order->customer->customer_name ? $dataPickup->order->customer->customer_name : ''}}</td>
                        <td>{{ $dataPickup->customerName ? $dataPickup->customerName->customer_name  : ''}}</td>
                        <td>{{ $dataPickup->order->order_date }}</td>
                        <td>{{ $dataPickup->pickup_date ? $dataPickup->pickup_date : 'belum diambil' }}</td>
                        <td>
                            <ul class="list-unstyled">
                                <li><span class="badge {{ $dataPickup->pickup_date ? "bg-light-success" : "bg-light-secondary" }}">{{ $dataPickup->pickup_date  ? "telah diambil" : "order belum diambil" }}</span></li>

                            </ul>
                            </td>
                        <td>
                        {{--<a href="{{ route('Transaksi.index', ['edit' => $dataPickup->id]) }}" class="btn btn-sm btn-warning">
                            Edit
                            </a> --}}

                            <a href="{{route('pickup.edit',$dataPickup->id)}}" class="btn btn-shadow {{ $dataPickup->pickup_date ? "btn-success" : 'btn-outline-success' }} "><div class="d-flex justify-content-center align-items-center gap-2 text-center"><i class="ti {{ $dataPickup->pickup_date ? " ti-truck-delivery text-white" : ' ti-truck-delivery text-green' }}  fs-5  "></i>{{ $dataPickup->pickup_date ? "Detail pickup" : 'Pickup' }}</div></a>
                            <form onclick="return confirm('Yakin ingin membatalkan {{ $dataPickup->order_code }} ?')" action="{{ route('pickup.destroy', $dataPickup->id) }}" method="post" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                <button class="btn btn-shadow btn-danger"><div class="d-flex justify-content-center align-items-center gap-2 text-center"><i class="ti ti-trash fs-5 text-white"></i>Batal</div></button>

                            </form>
                        </td>
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

{{-- @section('modal-create')
<div id="create" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Transaksi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('Transaksi.store') }}" method="post" >
          @csrf
      <div class="modal-body">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Nama</label>
                <input type="text" class="form-control" name="name">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" min="8">
            </div>

            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Level Transaksi</label>
                <select class="form-select" aria-label="Default select example" name="level">
                    <option selected disabled>--Pilih Level--</option>
                    @foreach ($levels as $keylevel)
                    <option value="{{ $keylevel->id }}">{{ $keylevel->level_name }}</option>
                    @endforeach
                </select>
            </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>

      </div>
    </form>
    </div>
  </div>
</div>

@endsection --}}

{{-- @section('modal-edit')
@if ($editTransaksi)
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      var modal = new bootstrap.Modal(document.getElementById('editModal'));
      modal.show();
    });
  </script>
@endif --}}

{{-- @if ($editTransaksi)
    <div id="editModal" class="modal fade show" tabindex="-1" aria-modal="true" style="display: block;" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('Transaksi.update', $editTransaksi->id) }}">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit Transaksi</h5>
          <a href="{{ route('Transaksi.index') }}" class="btn-close"></a>
        </div>
        <div class="modal-body">
          <input type="text" name="name" class="form-control mb-2" value="{{ $editTransaksi->name }}">
          <input type="email" name="email" class="form-control mb-2" value="{{ $editTransaksi->email }}">
          <input type="password" name="password" class="form-control mb-2" placeholder="Kosongkan jika tidak diubah">
          <select name="level" class="form-select mb-2">
            @foreach ($levels as $level)
              <option value="{{ $level->id }}" {{ $editTransaksi->id_level == $level->id ? 'selected' : '' }}>
                {{ $level->level_name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="modal-footer">
          <a href="{{ route('Transaksi.index') }}" class="btn btn-secondary">Batal</a>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif --}}





{{-- @endsection --}}

@section('js')

{{-- <script>


  $(document).ready(function () {
    $('#new-cons').on('click', '.editBtn', function () {
      const form = $('#editForm');
      form.attr('action', $(this).data('action'));

      $('#editName').val($(this).data('name'));
      $('#editEmail').val($(this).data('email'));
      $('#editLevel').val($(this).data('level'));
      $('#editPassword').val('');
    });
  });


</script> --}}


{{-- end edit form --}}


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
