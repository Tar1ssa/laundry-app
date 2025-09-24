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
                  <li class="breadcrumb-item" aria-current="page">Tambah Transaksi</li>
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
                <h3>Tambah Transaksi</h3>
              </div>
              <div class="card-body">
                <form action="{{ route('transaksi.store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                                    <div class="form-floating ">
                                        <input value="{{ $trans_number }}" type="text" class="form-control" id="floatingName" placeholder="Nama" name="trans_code" readonly>
                                        <label for="floatingName">Kode Transaksi</label>
                                    </div>
                        </div>
                    <div class="row">
                        <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-group">
                                    <label class="col-form-label text-lg-end">Customer</label>
                                    <div class="">
                                    <select
                                        class="form-control"
                                        data-trigger
                                        name="id_customer"
                                        id="choices-single-default"
                                    >
                                        <option value="" selected disabled>--Pilih customer--</option>
                                        @foreach ($customer as $keycustomer)

                                        <option value="{{ $keycustomer->id }}">{{ $keycustomer->customer_name }}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                                </div>

                        </div>
                        <div class="col-md-6 ">
                            <div class="mb-3 form-group">
                                <label for="" class="col-form-label text-lg-end">Tanggal order</label>
                                {{-- <input value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" type="date" class="form-control" id="floatinEmail" placeholder="" name="order_date" readonly> --}}
                                <input value="" type="date" class="form-control" id="floatinEmail" placeholder="" name="order_date">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h4>Detail order</h4>
                        <button type="button" id="addRow" class="btn btn-shadow btn-primary mb-3">Tambah pesanan</button>

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
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id='tableBody'></tbody>
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

                                <input type="number" name="total" id="total" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Bayar</label>
                                <div class="d-flex flex-row">
                                <div class="input-group-text">Rp.</div>

                                <input type="number" name="order_pay" id="order_pay" class="form-control" >
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Kembalian</label>
                                <div class="d-flex flex-row">
                                <div class="input-group-text">Rp.</div>

                                <input type="number" name="order_change" id="order_change" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-shadow btn-primary">Submit</button>
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
<script src="{{ asset('/assets/asset/js/plugins/choices.min.js') }}"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
        var genericExamples = document.querySelectorAll('[data-trigger]');
        for (i = 0; i < genericExamples.length; ++i) {
          var element = genericExamples[i];
          new Choices(element, {
            placeholderValue: 'This is a placeholder set in the config',
            searchPlaceholderValue: 'Cari customer'
          });
        }


        var serviceSearch = document.querySelectorAll('[data-service]');
        for (i = 0; i < serviceSearch.length; ++i) {
          var element = serviceSearch[i];
          new Choices(element, {
            placeholderValue: 'This is a placeholder set in the config',
            searchPlaceholderValue: 'Cari service'
          });
        }
    });
</script>

{{-- tambah table service --}}
<script>
        let count = 1;
    document.getElementById('addRow').addEventListener('click', function() {
        const tbody = document.querySelector('#tableTrans tbody');

        const no = count++;
        const tr = document.createElement('tr');
        tr.innerHTML = `
        <tr><td>${no}</td>
            <td><div class="">
                    <div class="">
                        <div class="">
                                    <select
                                        class="form-select form-select-sm"
                                        data-service
                                        name="id_service[]"
                                        id="choices-single-default"
                                    >
                                        <option value="" selected disabled>--Pilih service--</option>
                                        @foreach ($service as $keyservice)

                                        <option value="{{ $keyservice->id }}" data-price="{{ $keyservice->price }}">{{ $keyservice->service_name }}</option>
                                        @endforeach

                                    </select>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <div class="d-flex flex-row">
                    <input type="number" name="qty[]" class="form-control form-control-sm qty" id="" placeholder="Masukkan jumlah">
                    <div class="input-group-text">kg</div>
                </div>
            </td>
            <td>
            <div class="d-flex flex-row">
                <div class="input-group-text">Rp.</div>
                    <input value="" type="hidden" id='hiddenprice' name="price_service[]" class="form-control price" id="inlineFormInputGroupUsername" placeholder="" readonly>
                    <input value="" type="number" name="price_per_kg[]" class="form-control form-control-sm" id="inlineFormInputGroupUsername" placeholder="" readonly>
                    <div class="input-group-text">per kg</div>
                </div>
            </td>
            <td>
                <div class="d-flex flex-row">
                <div class="input-group-text">Rp.</div>
                <input value="" type="number" name="" class="form-control form-control-sm subtotal" readonly>
                <input value="" id='subtotale' type="hidden" name="subtotali[]" class="form-control subtotal" readonly>
                </div
            </td>
            <td><textarea class="form-control" id="" rows="1" name="note[]"></textarea></td>
            <td><button class='btn btn-danger delete-row' type='submit' >Hapus</button></td>
        </tr>`;

        tbody.appendChild(tr);
    });

    // let category = document.querySelector('#id_category');
    // category.addEventListener('change', async function(){
    //     const id_category = this.value; // artinya si selector id_category, mau ambil value
    //     const selectBooks = document.getElementById('id_books');
    //     selectBooks.innerHTML = "<option value='' selected disabled>--Pilih Buku--</option>";
    //     if (!category) {
    //         selectBooks.innerHTML = "<option value='' selected disabled>--Pilih Buku--</option>";
    //         return;
    //     }

    //     try {
    //         const res = await fetch(`/get-buku/${id_category}`);
    //         const data = await res.json();
    //         data.data.forEach(books => {
    //             const option = document.createElement('option');
    //             option.value = books.id;
    //             option.textContent = books.title;
    //             selectBooks.appendChild(option);
    //         });
    //     } catch (error) {
    //         console.log('error fetch buku', error);
    //     }
    // });

    document.querySelector('#tableTrans tbody').addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-row')) {
        e.target.closest('tr').remove();

    }
    });
</script>

<script>

    document.querySelector('#tableTrans tbody').addEventListener('change', function(e) {
    if (e.target.matches('[data-service]')) {
        const selectedOption = e.target.options[e.target.selectedIndex];
        const price = selectedOption.getAttribute('data-price') || 0;


        const row = e.target.closest('tr');
        const priceInput = row.querySelector('input[name="price_per_kg[]"]');
        const hidden = row.querySelector('input[name="price_service[]"]');
        if (priceInput) {
            const formattedNumber = new Intl.NumberFormat('id-ID').format(price);
            priceInput.value = formattedNumber;
            hidden.value = price;
            // priceInput.placeholderValue = formattedNumber;
        }
    }
});

</script>
<script>



// element references
const tbody = document.querySelector('#tableTrans tbody');
const totalEl = document.getElementById('total');
const bayarEl = document.getElementById('order_pay');
const kembalianEl = document.getElementById('order_change');


// hitung total dari semua hidden subtotals
function recalcTotal() {
  let total = 0;
  const tax = 0.05;
  let aftertax = 0;
  document.querySelectorAll("input[name='subtotali[]']").forEach(inp => {
    total += parseFloat(inp.value) || 0;
  });

  taxtotal = total*tax;
  aftertax = total - taxtotal;
  totalEl.value = aftertax.toFixed(0); // atau tanpa .toFixed jika mau number asli
  recalcKembalian();
}

// hitung kembalian (bayar - total)
function recalcKembalian() {
  const total = parseFloat(totalEl.value) || 0;
  const bayar = parseFloat(bayarEl.value) || 0;
  let kembalian = bayar - total;

  // jika ingin kembalian tidak negatif, uncomment baris berikut:
  if (kembalian < 0) kembalian = 0;
  kembalianEl.value = kembalian.toFixed(0);
}

// event delegation: update subtotal baris saat qty/price berubah
tbody.addEventListener('input', function(e) {
  if (e.target.classList.contains('qty') || e.target.classList.contains('price')) {
    const row = e.target.closest('tr');

    const qty = parseFloat((row.querySelector('.qty').value || '0').toString()) || 0;

    // ambil price sebagai string → bersihkan non-digit → parseFloat
    const priceInput = row.querySelector('.price');
    const rawPrice = (priceInput && (priceInput.value || '')).toString().replace(/[^\d.]/g, '');
    const price = parseFloat(rawPrice) || 0;

    const subtotal = qty * price;
    const subtotalformattedNumber = new Intl.NumberFormat('id-ID').format(subtotal);

    // update visible subtotal (input type=number) — pastikan selector memilih input yang bukan hidden
    const visibleSubtotal = row.querySelector('input[type="number"].subtotal:not([name="subtotali[]"])');
    if (visibleSubtotal) visibleSubtotal.value = subtotalformattedNumber;

    // update hidden raw subtotal (name="subtotali[]")
    const hiddenSubtotal = row.querySelector("input[name='subtotali[]']");
    if (hiddenSubtotal) hiddenSubtotal.value = subtotal.toFixed(0);

    // recalc grand total
    recalcTotal();
  }
});

// handling row delete (pastikan tombol delete bertipe="button")
tbody.addEventListener('click', function(e) {
  if (e.target.classList.contains('delete-row')) {
    e.preventDefault();
    const row = e.target.closest('tr');
    if (row) {
      row.remove();
      recalcTotal();
    }
  }
});

// recalc kembalian saat user input bayar
if (bayarEl) {
  bayarEl.addEventListener('input', recalcKembalian);
}

// inisialisasi awal (jika ada baris prefilled)
recalcTotal();

</script>

@endsection
