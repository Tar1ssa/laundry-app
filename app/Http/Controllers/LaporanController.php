<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trans_order;
use App\Models\Trans_order_detail;
use App\Models\Type_of_service;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Total transaksi (jumlah order)
        $totalTransactions = Trans_order::count();

        // Total transaksi bulan ini
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $transactionsThisMonth = Trans_order::whereYear('order_date', $currentYear)
            ->whereMonth('order_date', $currentMonth)
            ->count();

        // Pendapatan bulan ini (sum total dari transaksi bulan ini)
        $incomeThisMonth = Trans_order::whereYear('order_date', $currentYear)
            ->whereMonth('order_date', $currentMonth)
            ->sum('total');

        $services = Type_of_service::with('orderDetails')->get();

        $services->transform(function ($service) {
            $service->total_qty = $service->orderDetails->count('id');
            $service->total_revenue = $service->orderDetails->sum('subtotal');
            return $service;
        });

        // return $services;
        return view('admin.laporan.index', compact('totalTransactions', 'transactionsThisMonth', 'incomeThisMonth', 'services'));
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
