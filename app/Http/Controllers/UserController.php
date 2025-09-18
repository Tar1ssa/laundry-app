<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Level;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('level')->orderBy('id', 'desc')->get();
        $title = 'Users';

        $levels = Level::get();
        // return $users;

        // return view('admin.user.index', compact('title', 'users', 'levels'));
        // $editUser = null;
        // if ($request->has('edit')) {
        //     $editUser = User::find($request->edit);
        // }

        return view('admin.user.index', compact('users', 'levels', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah User';
        $levels = Level::get();
        return view('admin.user.create', compact('title', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // protected function friendlyMessage($th)
    // {
    //     if ($th instanceof \Illuminate\Database\QueryException) {
    //         if (str_contains($th->getMessage(), 'users_email_unique')) {
    //             return 'Email sudah digunakan.';
    //         }
    //     }
    //     return 'Silakan coba lagi.';
    // }

    public function store(Request $request)
    {
        try {

            $rules = [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8',
                'level' => 'required',
            ];

            $messages = [
                'email.unique' => 'Email sudah digunakan.',
                'password.min' => 'Password harus minimal 8 karakter.',
            ];

            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                $errors = $validation->errors();

                // Ambil pesan error spesifik untuk password jika ada
                if ($errors->has('email')) {
                    Alert::error('Gagal!', $errors->first('email'));
                } elseif ($errors->has('password')) {
                    Alert::error('Gagal!', $errors->first('password'));
                } else {
                    Alert::error('Gagal!', 'Terjadi kesalahan validasi. Silakan periksa kembali.');
                }

                return redirect()->back()->withErrors($errors)->withInput();
            }


            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'id_level' => $request->level,
            ]);
            Alert::success('Sukses!', 'User berhasil ditambahkan!');
            return redirect()->to('user')->with('Sukses!', 'User berhasil ditambahkan!');
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
        $edit = User::find($id);
        $title = 'Edit User';
        $levels = Level::get();
        return view('admin.user.edit', compact('title', 'edit', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'level' => 'required',
        ];

        $messages = [
            'password.min' => 'Password harus minimal 8 karakter.',
        ];

        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            $errors = $validation->errors();

            // Ambil pesan error spesifik untuk password jika ada
            if ($errors->has('password')) {
                Alert::error('Gagal!', $errors->first('password'));
            } else {
                Alert::error('Gagal!', 'Terjadi kesalahan validasi. Silakan periksa kembali.');
            }

            return redirect()->back()->withErrors($errors)->withInput();
        }

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->id_level = $request->level;

        if ($request->filled('password')) {
            $user->password = $request->password;
        }

        $user->save();

        Alert::success('Sukses!', 'User berhasil diupdate');
        return redirect()->route('user.index')->with('Sukses!', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        Alert::success('Sukses!', 'User berhasil dihapus');
        return redirect()->back()->with('Sukses!', 'User berhasil dihapus!');
    }
}
