<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type_of_service;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Services = Type_of_service::orderBy('id', 'desc')->get();
        $title = 'Jenis Service';
        // return $Services;

        // return view('admin.Service.index', compact('title', 'Services', 'levels'));
        // $editService = null;
        // if ($request->has('edit')) {
        //     $editService = Service::find($request->edit);
        // }

        return view('admin.service.index', compact('Services', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Jenis Service';
        return view('admin.service.create', compact('title',));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $rules = [
                'name' => 'required',
                'price' => 'required|numeric',
            ];

            $messages = [
                'name.required' => 'Nama service tidak dapat kosong.',
                'price.required' => 'Harga service tidak dapat kosong.',
                'price.numeric' => 'Harga harus berupa angka'
            ];

            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                $errors = $validation->errors();

                // Ambil pesan error spesifik untuk password jika ada
                if ($errors->has('name')) {
                    Alert::error('Gagal!', $errors->first('name'));
                } elseif ($errors->has('price')) {
                    Alert::error('Gagal!', $errors->first('price'));
                } else {
                    Alert::error('Gagal!', 'Terjadi kesalahan validasi. Silakan periksa kembali.');
                }

                return redirect()->back()->withErrors($errors)->withInput();
            }


            Type_of_service::create([
                'service_name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
            ]);
            Alert::success('Sukses!', 'Service berhasil ditambahkan!');
            return redirect()->to('service')->with('Sukses!', 'Service berhasil ditambahkan!');
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
        $edit = Type_of_service::find($id);
        $title = 'Edit Service';

        return view('admin.service.edit', compact('title', 'edit',));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => 'required',
            'price' => 'required|numeric',
        ];

        $messages = [
            'name.required' => 'Nama service tidak dapat kosong.',
            'price.required' => 'Harga service tidak dapat kosong.',
            'price.numeric' => 'Harga harus berupa angka'
        ];

        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            $errors = $validation->errors();

            // Ambil pesan error spesifik untuk password jika ada
            if ($errors->has('name')) {
                Alert::error('Gagal!', $errors->first('name'));
            } elseif ($errors->has('price')) {
                Alert::error('Gagal!', $errors->first('price'));
            } else {
                Alert::error('Gagal!', 'Terjadi kesalahan validasi. Silakan periksa kembali.');
            }

            return redirect()->back()->withErrors($errors)->withInput();
        }

        $Service = Type_of_service::findOrFail($id);
        $Service->service_name = $request->name;
        $Service->price = $request->price;
        $Service->description = $request->description;


        $Service->save();

        Alert::success('Sukses!', 'Service berhasil diupdate');
        return redirect()->route('service.index')->with('Sukses!', 'Service berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Service = Type_of_service::find($id);
        $Service->delete();
        Alert::success('Sukses!', 'Service berhasil dihapus');
        return redirect()->back()->with('Sukses!', 'Service berhasil dihapus!');
    }
}
