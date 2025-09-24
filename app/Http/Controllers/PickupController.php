<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Trans_order;
use Illuminate\Http\Request;
use App\Models\Trans_laundry_pickup;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class PickupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Pickup = Trans_laundry_pickup::with('customerName', 'order.customer')->get();
        $title = 'Pickup';
        // return $Pickup;
        return view('admin.pickup.index', compact('Pickup', 'title'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = 'Detail pickup';
        $customers = Customer::get();
        $edit = Trans_laundry_pickup::with('customerName', 'order.customer')->find($id);
        $show = Trans_order::with('customer', 'detailOrder.service')->find($edit->order->id);
        switch ($edit) {
            case $edit->order->order_status == 1:
                $order_status = 'Menunggu';
                break;

            case $edit->order->order_status == 2:
                $order_status = 'Proses';
                break;

            case $edit->order->order_status == 3:
                $order_status = 'Siap diambil';
                break;

            case $edit->order->order_status == 4:
                $order_status = 'Selesai';
                break;

            default:
                $order_status = 'tidak diketahui';
                break;
        }
        // return $edit;
        return view('admin.pickup.edit', compact('show', 'title', 'edit', 'customers', 'order_status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        try {
            $rules = [
                'customer_pickup' => 'required',

            ];

            $messages = [
                'customer_pickup.required' => 'Nama pengambil tidak dapat kosong.',

            ];

            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                $errors = $validation->errors();

                // Ambil pesan error spesifik untuk password jika ada
                if ($errors->has('customer_pickup')) {
                    Alert::error('Gagal!', $errors->first('customer_pickup'));
                } else {
                    Alert::error('Gagal!', 'Terjadi kesalahan validasi. Silakan periksa kembali.');
                }

                return redirect()->back()->withErrors($errors)->withInput();
            }
            $update = Trans_laundry_pickup::with('order')->find($id);
            $order_siap = Trans_order::find($update->order->id);
            $order_siap->order_status = 4;
            $update->id_customer = $request->customer_pickup;
            $update->notes =  $request->note;
            $update->pickup_date = $request->pickup_date;
            $update->save();
            $order_siap->save();

            Alert::success('Sukses!', 'Pickup berhasil!');
            return redirect()->to('pickup');
        }
        // catch (ValidationException $e) {
        //     return redirect()->back()->withErrors($e->validator)->withInput();
        // }
        catch (\Throwable $th) {
            Alert::error('Gagal!', 'Terjadi kesalahan: ' . ($th));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Pickup = Trans_laundry_pickup::find($id);
        $Pickup->order()->delete();
        $Pickup->delete();
        Alert::success('Sukses!', 'Transaksi berhasil dibatalkan!');
        return redirect()->to('transaksi');
    }
}
