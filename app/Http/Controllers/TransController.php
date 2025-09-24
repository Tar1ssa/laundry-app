<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Trans_order;
use App\Models\Trans_order_detail;
use Illuminate\Http\Request;
use App\Models\Trans_laundry_pickup;
use App\Models\Type_of_service;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Pest\ArchPresets\Custom;

class TransController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Transaksi = Trans_order::with('customer', 'detailOrder.service')->orderBy('id', 'desc')->get();
        $title = 'Data Transaksi';
        return view('admin.transaksi.index', compact('Transaksi', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Buat transaksi';
        $service = Type_of_service::get();
        $customer = Customer::get();

        $code = 'TRNSC'; // Set a fixed transaction code prefix
        $today = Carbon::now()->format('Ymd'); // Get today's date in 'YYYYMMDD' format using Carbon
        $prefix = $code . '-' . $today; // Combine the code and date to form a transaction prefix like 'TRNSC-20250903'
        $todayfix = Carbon::now()->toDateString();
        $lasttransaction = Trans_order::whereDate('created_at', $todayfix) // Filter Borrows records created today
            ->orderBy('id', 'desc') // Sort by ID in descending order to get the latest entry
            ->first(); // Retrieve the first (latest) record from the filtered results
        if ($lasttransaction) { // Check if a transaction was found for today
            $lastNumber = (int) substr($lasttransaction->order_code, -3); // Extract the last 3 digits of the transaction number and convert to integer
            $newNumber = str_pad($lastNumber + 1, 3, "0", STR_PAD_LEFT); // Increment the number by 1 and pad it to 3 digits with leading zeros
        } else {
            $newNumber = '001'; // If no transaction exists for today, set the new number to 'Nol' (likely placeholder or default)
        }
        $trans_number = $prefix . $newNumber;


        return view('admin.transaksi.create', compact('title', 'service', 'customer', 'trans_number', 'today'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $rules = [
                'id_customer' => 'required',
                'order_date' => 'required',
            ];

            $messages = [
                'id_customer.required' => 'Nama customer tidak dapat kosong.',
                'order_date.required' => 'Tanggal order tidak dapat kosong.',

            ];

            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                $errors = $validation->errors();

                // Ambil pesan error spesifik untuk password jika ada
                if ($errors->has('id_customer')) {
                    Alert::error('Gagal!', $errors->first('id_customer'));
                } elseif ($errors->has('order_date')) {
                    Alert::error('Gagal!', $errors->first('order_date'));
                } else {
                    Alert::error('Gagal!', 'Terjadi kesalahan validasi. Silakan periksa kembali.');
                }

                return redirect()->back()->withErrors($errors)->withInput()->with('sweet_alert', true);
            }



            $create = [
                'id_customer' => $request->id_customer,
                'order_code' => $request->trans_code,
                'order_date' => $request->order_date,
                'order_pay' => $request->order_pay,
                'order_change' => $request->order_change,

                'total' => $request->total
            ];
            if ($request->order_pay > $request->total) {
                $order_end_date = \Carbon\Carbon::now();
                $create['order_end_date'] =  $order_end_date;
            }

            $insertOrder = Trans_order::create($create);

            foreach ($request->id_service as $key => $value) {
                Trans_order_detail::create([
                    'id_order' => $insertOrder->id,
                    'id_service' => $request->id_service[$key],
                    'qty' => $request->qty[$key],
                    'subtotal' => $request->subtotali[$key],
                    'notes'  => $request->note[$key]
                ]);
            }

            DB::commit();
            Alert::success('Sukses!', 'Transaksi berhasil dibuat!');
            return redirect()->to('transaksi')->with('Sukses!', 'Transaksi berhasil dibuat!');
            // Alert::success('Success', 'Transaksi berhasil dibuat');
            // return redirect()->to('transactions');


            // return redirect()->route('print-borrowed', $insertBorrow->id);


            // return to_route('print-borrowed', ['id' => $insertBorrow]);

            // return $insertBorrow->id;
            // return redirect()->to('print-borrowed', $insertBorrow->id);
        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error('oops!', $th->getMessage());
            return redirect()->back()->withErrors(['Error' => 'transaksi gagal']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $show = Trans_order::with('customer', 'detailOrder.service')->find($id);
        $title = 'Detail Transaksi';
        // return $show ;
        return view('admin.transaksi.show', compact('show', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {

            $trans = Trans_order::find($id);
            $trans->order_change = $request->order_change;
            $trans->order_pay = $request->order_pay;
            if ($request->order_pay > $trans->total) {
                $trans->order_end_date = \Carbon\Carbon::now();
                if ($trans->order_end_date && $trans->order_status == 3) {
                    Trans_laundry_pickup::create([
                        'id_order' => $trans->id
                    ]);
                }
            }
            $trans->save();


            DB::commit();
            Alert::success('Sukses!', 'Transaksi berhasil dibayar!');
            return redirect()->to('transaksi')->with('Sukses!', 'Transaksi berhasil dibayar!');
            // Alert::success('Success', 'Transaksi berhasil dibuat');
            // return redirect()->to('transactions');


            // return redirect()->route('print-borrowed', $insertBorrow->id);


            // return to_route('print-borrowed', ['id' => $insertBorrow]);

            // return $insertBorrow->id;
            // return redirect()->to('print-borrowed', $insertBorrow->id);
        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error('oops!', $th->getMessage());
            return redirect()->back()->withErrors(['Error' => 'transaksi gagal']);
        }
    }

    public function done(Request $request, string $id)
    {
        $done = Trans_order::find($id);
        $done->order_status = 3;
        $done->save();
        if ($done->order_status == 3 && $done->order_end_date) {
            Trans_laundry_pickup::create([
                'id_order' => $done->id
            ]);
        }
        Alert::success('Sukses!', 'Order siap diambil!');
        return redirect()->back()->with('Sukses!', 'Order siap diambil!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Trans = Trans_order::find($id);
        $detailOrder = Trans_order_detail::where('id_order', $Trans->id);
        $pickup = Trans_laundry_pickup::where('id_order', $Trans->id);
        $pickup->delete();
        $detailOrder->delete();
        $Trans->delete();
        Alert::success('Sukses!', 'Transaksi berhasil dibatalkan!');
        return redirect()->to('transaksi');
    }

    public function laundryTrans()
    {
        $laundryCustomer = Customer::get();
        $laundryLayanan = Type_of_service::get();

        return view('admin.transaksi.laundry-trans', compact('laundryLayanan', 'laundryCustomer'));
    }

    public function getCustomerDataById($id_customer)
    {
        try {
            $customerData = Customer::where('id', $id_customer)->get();
            return response()->json(['status' => 'success', 'message' => 'fetch berhasil', 'data' => $customerData]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    public function getLayanan()
    {
        $layanan = Type_of_service::all();
        $prices = $layanan->pluck('price', 'service_name');
        return response()->json($prices);
    }

    public function getTranscode()
    {


        $code = 'TRNSC'; // Set a fixed transaction code prefix
        $today = Carbon::now()->format('Ymd'); // Get today's date in 'YYYYMMDD' format using Carbon
        $prefix = $code . '-' . $today; // Combine the code and date to form a transaction prefix like 'TRNSC-20250903'
        $todayfix = Carbon::now()->toDateString();
        $lasttransaction = Trans_order::whereDate('created_at', $todayfix) // Filter Borrows records created today
            ->orderBy('id', 'desc') // Sort by ID in descending order to get the latest entry
            ->first(); // Retrieve the first (latest) record from the filtered results
        if ($lasttransaction) { // Check if a transaction was found for today
            $lastNumber = (int) substr($lasttransaction->order_code, -3); // Extract the last 3 digits of the transaction number and convert to integer
            $newNumber = str_pad($lastNumber + 1, 3, "0", STR_PAD_LEFT); // Increment the number by 1 and pad it to 3 digits with leading zeros
        } else {
            $newNumber = '001'; // If no transaction exists for today, set the new number to 'Nol' (likely placeholder or default)
        }
        $trans_number = $prefix . $newNumber;
        return response()->json($trans_number);
    }

    public function LaundryStore(Request $request)
    {
        // $request->validate([
        //     'id' => 'required|string|unique:trans_orders,order_code',
        //     'customer.id' => 'required|exists:customers,id',
        //     'items' => 'required|array|min:1',
        //     'order_date' => 'required|date',
        //     'order_status' => 'required',
        //     'total' => 'required|numeric|min:0'
        // ]);

        DB::beginTransaction();

        try {
            $order = Trans_order::create([
                'order_code' => $request->id,
                'id_customer' => $request->customer['id'],
                'order_date' => \Carbon\Carbon::now(),
                'order_status' => $request->order_status,
                'total' => $request->total
            ]);

            foreach ($request->items as $item) {
                Trans_order_detail::create([
                    'id_order' => $order->id,
                    'id_service' => $item['id'],
                    'qty' => $item['weight'],
                    'subtotal' => $item['subtotal'],
                    'notes' => $item['notes'] ?? null
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'data' => $order
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal Menyimpan Traansaksi',
                'error' => $th->getMessage()
            ], 522);
        }
    }

    public function getTransaksi()
    {
        $transactions = Trans_order::with(['customer', 'detailOrder.service'])->get();
        $data = $transactions->map(function ($trx) {
            return [
                'id_order' => $trx->id,
                'id' => $trx->order_code,
                'customer' => [
                    'id' => $trx->customer->id ?? null,
                    'name' => $trx->customer->customer_name ?? null,
                    'phone' => $trx->customer->phone ?? null,
                    'address' => $trx->customer->address ?? null,
                ],
                'items' => $trx->detailOrder->map(function ($orderDetail) {
                    return [
                        'id' => $orderDetail->id,
                        'service' => $orderDetail->service->service_name ?? null,
                        'weight' => $orderDetail->qty,
                        'price' => $orderDetail->service->price ?? null,
                        'subtotal' => $orderDetail->subtotal,
                        'notes' => $orderDetail->notes ?? null
                    ];
                }),
                'order_pay' => $trx->order_pay ?? null,
                'total' => $trx->total,
                'date' => $trx->order_date ?? null,
                'order_status' => $trx->order_status,
            ];
        });
        return response()->json($data);
    }

    public function setStatus(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'required|in:1,2,3,4' // misalnya ada banyak status
        ]);



        try {
            DB::transaction(function () use ($request, $id) {
                $order = Trans_order::where('id', $id)->firstOrFail();
                $order->update([
                    'order_status' => $request->order_status,
                ]);
            });

            $order = Trans_order::where('id', $id)->firstOrFail();
            if ($request->order_status == 3 && $request->order_pay) {
                Trans_laundry_pickup::create([
                    'id_order' => $order->id,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Status berhasil diupdate',
                'pay' => $order->order_pay
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error' . $th->getMessage(),
                'id' => $id,
                'order_status' => $request->order_status,
            ], 500);
        }
    }

    public function listLayanan()
    {
        $service = Type_of_service::all();
        return response()->json($service);
    }
}
