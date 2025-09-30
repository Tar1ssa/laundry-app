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
                  <li class="breadcrumb-item" aria-current="page"><a href="#">Transaksi</a></li>
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
                <h3>Data Transaksi</h3>
                {{-- laundry trans button --}}
                <div>

                    <a href="{{ route('transaksi.create') }}" class="btn btn-shadow btn-primary">Tambah Transaksi</a>
                    <a href="{{ route('laundry.transc') }}" class="btn btn-shadow btn-info">Sistem informasi laundry</a>
                </div>
                {{-- end laundry trans button --}}
                {{-- <a href="{{ route('transaksi.create') }}" class="btn btn-shadow btn-primary">Tambah Transaksi</a> --}}


                {{-- <button class="btn btn-shadow btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#create">Tambah Transaksi</button> --}}
              </div>
              <div class="card-body">
                <div class=" table-responsive">
                  <table id="new-cons" class="display table table-striped table-hover dt-responsive nowrap" style="width: 100%">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>No.Transaksi</th>
                        <th>Nama customer</th>
                        <th>Tanggal transaksi</th>
                        <th>Tanggal transaksi selesai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>

                        @foreach ($Transaksi as $index => $dataTransaksi)
                        @php
                            switch ($dataTransaksi) {
                                case $dataTransaksi->order_status == 1:
                                    $order_status = 'Menunggu';
                                    $order_badge = 'bg-light-secondary';
                                    break;

                                case $dataTransaksi->order_status == 2:
                                    $order_status = 'Proses';
                                    $order_badge = 'bg-light-warning';
                                    break;

                                case $dataTransaksi->order_status == 3:
                                    $order_status = 'Siap diambil';
                                    $order_badge = 'bg-light-primary';
                                    break;

                                case $dataTransaksi->order_status == 4:
                                    $order_status = 'Selesai';
                                    $order_badge = 'bg-light-success';
                                    break;

                                default:
                                    $order_status = 'tidak diketahui';
                                    break;
                            }
                        @endphp

                      <tr>
                        <td>{{ $index +=1 }}</td>
                        <td>{{ $dataTransaksi->order_code}}</td>
                        <td>{{ $dataTransaksi->customer->customer_name }}</td>
                        <td>{{ $dataTransaksi->order_date }}</td>
                        <td>{{ $dataTransaksi->order_end_date ? $dataTransaksi->order_end_date : "belum selesai" }}</td>
                        <td>
                            <ul class="list-unstyled">
                                <li><span class="badge {{ $order_badge }}">{{ $order_status }}</span></li>
                                <li><span class="badge {{ $dataTransaksi->order_end_date ? "bg-light-success" : "bg-light-secondary" }} ">{{ $dataTransaksi->order_end_date ? "lunas" : "belum lunas" }}</span></li>
                            </ul>
                            </td>
                        <td>
                            @if ($dataTransaksi->order_pay)
                                @php
                                    $total = $dataTransaksi->detailOrder->sum('subtotal');
                                    $dataWithTotal = $dataTransaksi->toArray();
                                    $dataWithTotal['total'] = $total;
                                @endphp
                            <button
                                type="button"
                                class="btn btn-shadow btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#exampleModal"
                                onclick='showReceipt(@json($dataWithTotal))'>
                                Print Struk
                            </button>
                            @endif
                            <button type="button" onclick='statusChangeOpen(@json($dataTransaksi))' class="btn btn-shadow btn-warning"  data-bs-toggle="modal" data-bs-target="#changeStatus"><div class="d-flex justify-content-center align-items-center gap-2 text-center"><i class="ti ti-checkbox fs-5 text-white"></i>Ubah status</div></button>
                            {{-- <form onclick="return confirm('Yakin ingin menandai transaksi {{ $dataTransaksi->order_code }} siap diambil ?')" action="{{ route('transaksi.done', $dataTransaksi->id) }}" method="post" class="d-inline">
                                    @csrf
                                    @method('PUT')

                            </form> --}}
                            <a href="{{route('transaksi.show',$dataTransaksi->id)}}" class="btn btn-shadow {{ $dataTransaksi->order_end_date ? "btn-success" : 'btn-outline-success' }} "><div class="d-flex justify-content-center align-items-center gap-2 text-center"><i class="ti {{ $dataTransaksi->order_end_date ? "ti-history text-white" : 'ti-cash text-green' }}  fs-5  "></i>{{ $dataTransaksi->order_end_date ? "Detail transaksi" : 'Bayar transaksi' }}</div></a>
                            <form onclick="return confirm('Yakin ingin membatalkan {{ $dataTransaksi->order_code }} ?')" action="{{ route('transaksi.destroy', $dataTransaksi->id) }}" method="post" class="d-inline">
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

{{-- changeStatus Modal --}}
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="changeStatus" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="changeStatusLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="changeStatusModal">

    </div>
  </div>
</div>
{{-- end changeStatus Modal --}}

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Struk</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modal-content">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Selesai</button>
        <button type="button" class="btn btn-primary" onclick="printReceipt()">Print</button>
      </div>
    </div>
  </div>
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

<script>
    function showReceipt(transaction) {
        console.log(transaction);
        const receiptHtml = `
            <div class="receipt">
                <div class="receipt-header">
                    <h2>🧺 LAUNDRY RECEIPT</h2>
                    <p>No. Transaksi: ${transaction.order_code}</p>
                    <p>Tanggal: ${new Date(transaction.order_date).toLocaleDateString('id-ID')}</p>
                </div>

                <div style="margin-bottom: 20px;">
                    <strong>Pelanggan:</strong><br>
                    ${transaction.customer.customer_name}<br>
                    ${transaction.customer.phone ?? ''}<br>
                    ${transaction.customer.address ?? ''}
                </div>

                <div style="margin-bottom: 20px;">
                    <strong>Detail Pesanan:</strong><br>
                    ${
                        transaction.detail_order?.map(item => `
                            <div class="receipt-item d-flex justify-content-between">
                                <span>${item.service?.service_name ?? 'Layanan tidak diketahui'} (${item.qty} kg)</span>
                                <span>Rp ${Number(item.subtotal).toLocaleString('id-ID')}</span>
                            </div>
                        `).join('') ?? '<em>Tidak ada item</em>'
                    }
                </div>

                <div class="receipt-total d-flex justify-content-between fw-bold border-top pt-2">
                    <span>Total</span>
                    <span>Rp ${Number(transaction.total).toLocaleString('id-ID')}</span>
                </div>

                <div style="text-align: center; margin-top: 20px;">
                    <p>Terima kasih atas kepercayaan Anda!</p>
                    <p>Barang akan siap dalam 1–2 hari kerja</p>
                </div>
            </div>
        `;

        document.getElementById('modal-content').innerHTML = receiptHtml;
    }
</script>

<script>

    function printReceipt() {
        const receiptContent = document.getElementById('modal-content').innerHTML;

        // Buat jendela popup baru
        const printWindow = window.open('', '', 'height=600,width=400');

        printWindow.document.write(`
            <html>
                <head>
                    <title>Cetak Struk</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            font-size: 14px;
                            padding: 20px;
                        }
                        .receipt-header {
                            text-align: center;
                            margin-bottom: 20px;
                        }
                        .receipt-item, .receipt-total {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 5px;
                        }
                        .receipt-total {
                            font-weight: bold;
                            border-top: 1px dashed #000;
                            padding-top: 10px;
                        }
                    </style>
                </head>
                <body onload="window.print(); window.close();">
                    ${receiptContent}
                </body>
            </html>
        `);

        printWindow.document.close();
    }
</script>

<script>
    function statusChangeOpen(transaksi) {
        const changeStatusRoute = @json(route('transaksi.done', ':id'));
        const changeStatusHtml = `
            <form action="${changeStatusRoute.replace(':id', transaksi.id)}" method="post">
                @csrf
                @method('PUT')
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="changeStatusLabel">Ubah Status</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body" >
                    <div class="mb-3">
                        <h2 class="fw-bold">${transaksi.order_code}</h2>
                        <h3 class="fw-bold">${transaksi.customer.customer_name}</h3>
                        <p></p>
                    </div>
                    <label for="" class="form-label">Pilih status baru:</label>
                    <select name="orderNewStatus" class="form-control" id="">
                        <option value="1"  ${transaksi.order_status == 1 ? 'selected' : ''}>Menunggu</option>
                        <option value="2"  ${transaksi.order_status == 2 ? 'selected' : ''}>Proses</option>
                        <option value="3"  ${transaksi.order_status == 3 ? 'selected' : ''}>Siap diambil</option>
                        <option value="4"  ${transaksi.order_status == 4 ? 'selected' : ''}>Selesai</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        `;

        document.getElementById('changeStatusModal').innerHTML = changeStatusHtml;
    }
</script>
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
