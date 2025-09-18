<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Data Level';
        $levels = Level::get();
        return view('admin.level.index', compact('levels', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Level';
        return view('admin.level.create', compact('title',));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $rules = [
                'name' => 'required',
            ];

            $messages = [
                'name.required' => 'Nama tidak dapat kosong.',
            ];

            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                $errors = $validation->errors();

                // Ambil pesan error spesifik untuk password jika ada
                if ($errors->has('name')) {
                    Alert::error('Gagal!', $errors->first('name'));
                }
                return redirect()->back()->withErrors($errors)->withInput();
            }


            Level::create([
                'level_name' => $request->name,
            ]);
            Alert::success('Sukses!', 'Level berhasil ditambahkan!');
            return redirect()->to('level')->with('Sukses!', 'Level berhasil ditambahkan!');
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
        $edit = Level::find($id);
        $title = 'Edit Level';

        return view('admin.level.edit', compact('title', 'edit'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => 'required',
        ];

        $messages = [
            'name.required' => 'Nama tidak dapat kosong.',
        ];

        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            $errors = $validation->errors();

            // Ambil pesan error spesifik untuk password jika ada
            if ($errors->has('name')) {
                Alert::error('Gagal!', $errors->first('name'));
            } else {
                Alert::error('Gagal!', 'Terjadi kesalahan validasi. Silakan periksa kembali.');
            }

            return redirect()->back()->withErrors($errors)->withInput();
        }

        $Level = Level::findOrFail($id);
        $Level->level_name = $request->name;

        $Level->save();

        Alert::success('Sukses!', 'Level berhasil diupdate');
        return redirect()->route('level.index')->with('Sukses!', 'Level berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Level = Level::find($id);
        $Level->delete();
        Alert::success('Sukses!', 'Level berhasil dihapus');
        return redirect()->back()->with('Sukses!', 'Level berhasil dihapus!');
    }
}
