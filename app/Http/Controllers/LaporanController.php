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
    public function index(Request $request)
    {
        // ambil input user
    $from = $request->input('from');
    $to = $request->input('to');

    // default: bulan ini
    if (!$from || !$to) {
        $from = Carbon::now()->startOfMonth()->toDateString();
        $to = Carbon::now()->endOfMonth()->toDateString();
    }

     // total transaksi seluruhnya
    $totalTransactions = Trans_order::count();

    // transaksi & income dalam range
    $transactionsThisRange = Trans_order::whereBetween('order_date', [$from, $to])->count();
    $incomeThisRange       = Trans_order::whereBetween('order_date', [$from, $to])->sum('total');

    // statistik layanan sesuai range
    $services = Type_of_service::with(['orderDetails' => function ($q) use ($from, $to) {
        $q->whereHas('order', function ($query) use ($from, $to) {
            $query->whereBetween('order_date', [$from, $to]);
        });
    }, 'orderDetails.order'])->get();

    $services->transform(function ($service) {
        $service->total_qty = $service->orderDetails->count('id');
        $service->total_revenue = $service->orderDetails->sum('subtotal');
        return $service;
    });

    return view('admin.laporan.index', [
        'totalTransactions' => $totalTransactions,
        'transactionsThisRange' => $transactionsThisRange,
        'incomeThisRange' => $incomeThisRange,
        'services' => $services,
        'hari_pertama' => Carbon::parse($from)->format('d-m-Y'),
        'hari_terakhir' => Carbon::parse($to)->format('d-m-Y'),
    ]);
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
