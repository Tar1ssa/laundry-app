<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trans_order;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order_selesai = Trans_order::where('order_status', 4)->get();
        $order_berjalan = Trans_order::where('order_status', '!=', 4)->get();
        $total_user = User::all();
        return view('admin.dashboard.index', compact('order_selesai', 'order_berjalan', 'total_user'));
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
