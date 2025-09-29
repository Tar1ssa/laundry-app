<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Customers = Customer::with('transaction')->orderBy('id', 'desc')->get();
        $title = 'Customers';
        return view('admin.customer.index', compact('Customers', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Customer';
        return view('admin.customer.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $rules = [
                'name' => 'required',
                'phone' => 'required|numeric|unique:customers',
            ];

            $messages = [
                'phone.unique' => 'No.Telp sudah digunakan.',
                'phone.numeric' => 'No.Telp tidak valid',
                'phone.required' => 'No.Telp tidak dapat kosong',
                'name.required' => 'Nama tidak dapat kosong.',
            ];

            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                $errors = $validation->errors();

                // Ambil pesan error spesifik untuk password jika ada
                if ($errors->has('phone')) {
                    Alert::error('Gagal!', $errors->first('phone'));
                } elseif ($errors->has('name')) {
                    Alert::error('Gagal!', $errors->first('name'));
                } else {
                    Alert::error('Gagal!', 'Terjadi kesalahan validasi. Silakan periksa kembali.');
                }

                return redirect()->back()->withErrors($errors)->withInput();
            }


            Customer::create([
                'customer_name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
            Alert::success('Sukses!', 'Customer berhasil ditambahkan!');
            return redirect()->to('customer')->with('Sukses!', 'Customer berhasil ditambahkan!');
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
        $edit = Customer::find($id);
        $title = 'Edit Customer';
        return view('admin.customer.edit', compact('title', 'edit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => 'required',
            'phone' => 'required|numeric',
        ];

        $messages = [
            'phone.numeric' => 'No.Telp tidak valid',
            'phone.required' => 'No.Telp tidak dapat kosong',
            'name.required' => 'Nama tidak dapat kosong.',
        ];

        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            $errors = $validation->errors();

            // Ambil pesan error spesifik untuk password jika ada
            if ($errors->has('phone')) {
                Alert::error('Gagal!', $errors->first('phone'));
            } elseif ($errors->has('name')) {
                Alert::error('Gagal!', $errors->first('name'));
            } else {
                Alert::error('Gagal!', 'Terjadi kesalahan validasi. Silakan periksa kembali.');
            }

            return redirect()->back()->withErrors($errors)->withInput();
        }

        $Customer = Customer::findOrFail($id);
        $Customer->customer_name = $request->name;
        $Customer->phone = $request->phone;
        $Customer->address = $request->address;

        $Customer->save();

        Alert::success('Sukses!', 'Customer berhasil diupdate');
        return redirect()->route('customer.index')->with('Sukses!', 'Customer berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Customer = Customer::find($id);
        $Customer->delete();
        Alert::success('Sukses!', 'Customer berhasil dihapus');
        return redirect()->back()->with('Sukses!', 'Customer berhasil dihapus!');
    }
}
