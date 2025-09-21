<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Trans_order;
use Illuminate\Http\Request;
use App\Models\Trans_laundry_pickup;
use RealRashid\SweetAlert\Facades\Alert;

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
        return view('admin.pickup.index',compact('Pickup', 'title'));
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
        return view('admin.pickup.edit', compact('show', 'title', 'edit', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $update = Trans_laundry_pickup::find($id);
        $update->id_customer = $request->customer_pickup;
        $update->notes =  $request->note;
        $update->pickup_date = $request->pickup_date;
        $update->save();

        Alert::success('Sukses!', 'Pickup berhasil!');
        return redirect()->to('pickup');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
