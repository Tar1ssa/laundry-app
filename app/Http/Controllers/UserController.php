<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = [
            [
                'full_name' => 'carl johnson cinder',
                'email' => 'carl.johnson@example.com',
                'phone' => '081234567890'
            ],
            [
                'full_name' => 'maria clara santos',
                'email' => 'maria.santos@example.com',
                'phone' => '082345678901'
            ],
            [
                'full_name' => 'john doe',
                'email' => 'john.doe@example.com',
                'phone' => '083456789012'
            ]
        ];
        $title = 'Users';

        $level = Level::get();
        return view('admin.user.index', compact('title', 'users', 'level'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create');
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
