@extends('admin.app')
@section('content')
<div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
          <div class="page-block">
            <div class="row align-items-center">
              <div class="col-md-12">
                <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                  <li class="breadcrumb-item" aria-current="page">Detail Transaksi</li>
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
                <h3>Detail Transaksi</h3>
              </div>
              <div class="card-body">
                <form action="{{ route('transaksi.update',$show->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                                    <div class="form-floating ">
                                        <input value="{{ $show->order_code }}" type="text" class="form-control" id="floatingName" placeholder="Nama" name="trans_code" readonly>
                                        <label for="floatingName">Kode Transaksi</label>
                                    </div>
                        </div>
                    <div class="row">
                        <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-group">
                                    <label class="col-form-label text-lg-end">Customer</label>
                                    <div class="">
                                    <input type="text" class="form-control" readonly value="{{$show->customer->customer_name}}">

                                    </div>
                                </div>
                                </div>

                        </div>
                        <div class="col-md-6 ">
                            <div class="mb-3 form-group">
                                <label for="" class="col-form-label text-lg-end">Tanggal order</label>
                                {{-- <input value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" type="date" class="form-control" id="floatinEmail" placeholder="" name="order_date" readonly> --}}
                                <input value="{{ $show->order_date }}" type="date" class="form-control" id="floatinEmail" placeholder="" name="order_date" readonly>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h4>Detail order</h4>

                            <div class="table table-responsive">
                            <table id='tableTrans' class="display table table-striped table-hover dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Service</th>
                                        <th>Qty</th>
                                        <th>Harga service (per kg)</th>
                                        <th>Subtotal</th>
                                        <th>Catatan</th>

                                    </tr>
                                </thead>
                                <tbody id='tableBody'>
                                    @foreach ( $show->detailOrder as $index => $keyorder)
                                    <tr>
                                        <td>{{ $index +=1 }}</td>
                                        <td>{{ $keyorder->service->service_name }}</td>
                                        <td>{{$keyorder->qty}}-kg</td>
                                        <td>{{ "Rp.". number_format($keyorder->service->price, 2, ',','.')}}</td>
                                        <td>{{"Rp.". number_format($keyorder->subtotal, 2, ',','.')}}</td>
                                        <td>{{$keyorder->notes}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="" class="form-label">Total</label>
                                <div class="d-flex flex-row">
                                <div class="input-group-text">Rp.</div>
                                <input type="hidden" name="totalhidden" id="totalhidden" class="form-control" readonly>
                                <input type="number" name="total" id="total" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Bayar</label>
                                <div class="d-flex flex-row">
                                <div class="input-group-text">Rp.</div>
                                <input type="number" name="order_pay" id="order_pay" class="form-control" value="{{$show->order_pay}}" {{$show->order_end_date ? 'readonly' : ''}}>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Kembalian</label>
                                <div class="d-flex flex-row">
                                <div class="input-group-text">Rp.</div>
                                <input type="hidden" name="order_changehidden" id="order_changehidden" class="form-control" readonly>
                                <input type="number" name="order_change" id="order_change" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! $show->order_end_date ? '' : '<button type="submit" class="btn btn-shadow btn-primary">Submit</button>' !!}

                    <a href="{{ route('transaksi.index') }}" class="btn btn-shadow btn-secondary">Kembali</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
</div>
@endsection


@section('js')


{{-- Detail table service --}}


<script>
function cleanNumber(str) {
  if (!str) return 0;
  // hapus semua kecuali digit, koma, titik
  let cleaned = str.replace(/[^0-9,.-]/g, '');
  // ganti titik (sebagai pemisah ribuan) → kosong
  cleaned = cleaned.replace(/\./g, '');
  // ganti koma (decimal separator Indonesia) → titik
  cleaned = cleaned.replace(/,/g, '.');
  return parseFloat(cleaned) || 0;
}


const tbody = document.querySelector('#tableBody');
const totalEl = document.getElementById('total');
const bayarEl = document.getElementById('order_pay');
const kembalianEl = document.getElementById('order_change');

function recalcTotal() {
  let total = 0;
  tbody.querySelectorAll('tr').forEach(row => {
    const subtotalCell = row.querySelector('td:nth-child(5)');
    if (subtotalCell) {
      total += cleanNumber(subtotalCell.textContent);
    }
  });

  totalEl.value = total.toFixed(0);
  recalcKembalian();
}

function recalcKembalian() {
  const total = parseFloat(totalEl.value) || 0;
  const bayar = parseFloat(bayarEl.value) || 0;
  let kembalian = bayar - total;
    // jika bayar kurang dari total, paksa jadi 0
  if (kembalian < 0) {
    kembalian = 0;
  }
  kembalianEl.value = kembalian.toFixed(0);
}

if (bayarEl) {
  bayarEl.addEventListener('input', recalcKembalian);
}

// init awal
recalcTotal();

</script>

@endsection
