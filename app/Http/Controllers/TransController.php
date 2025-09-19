<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Trans_order;
use Illuminate\Http\Request;
use App\Models\Type_of_service;

class TransController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Transaksi = Trans_order::orderBy('id', 'desc')->get();
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
        $lasttransaction = Trans_order::whereDate('created_at', Carbon::today()) // Filter Borrows records created today
            ->orderBy('id', 'desc') // Sort by ID in descending order to get the latest entry
            ->first(); // Retrieve the first (latest) record from the filtered results
        if ($lasttransaction) { // Check if a transaction was found for today
            $lastNumber = (int) substr($lasttransaction->trans_number, -3); // Extract the last 3 digits of the transaction number and convert to integer
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
