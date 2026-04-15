<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $levels = DB::table('tbl_levels')->get();
        $users = DB::table('users')
            ->latest()
            ->join('tbl_levels', 'tbl_levels.id_level', '=', 'users.id_level')
            ->select('users.*', 'tbl_levels.nama_level')
            ->paginate(5);
        return view('user.index', compact('users', 'levels'))
            ->with('no', (request()->input('page', 1) - 1) * 5);
    }

    public function indexProfile($id)
    {
        $levels = DB::table('tbl_levels')->get();
        $users = DB::table('users')
            ->latest()
            ->join('tbl_levels', 'tbl_levels.id_level', '=', 'users.id_level')
            ->select('users.*', 'tbl_levels.nama_level')
            ->where('users.id', $id)
            ->get();
        $transaksi = DB::table('tbl_transaksis')->where('tbl_transaksis.id_user', $id)->get();
        return view('user.profile.index', compact('users', 'levels', 'transaksi'));
    }

    public function tambah(StoreUserRequest $request)
    {
        $validated = $request->validated();

        DB::table('users')->insert([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'id_level' => $validated['id_level'],
            'password' => Hash::make($validated['password']),
            'created_at' => now(),
        ]);

        return redirect('/user')->with('success', 'Data berhasil dibuat.');
    }

    public function hapus($id)
    {
        DB::table('users')->where('id', $id)->delete();

        return redirect('/user')->with('danger', 'Data berhasil dihapus.');
    }
    public function edit(UpdateUserRequest $request)
    {
        $validated = $request->validated();
        $id = $request->route('id') ?? $validated['id'] ?? $request->id;

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'id_level' => $validated['id_level'],
            'password' => Hash::make($validated['password']),
        ];

        if ($request->hasFile('gambar_user')) {
            $file = $request->file('gambar_user');
            $namaFile = $file->hashName();
            $file->move(public_path('assets/img/user/'), $namaFile);

            $payload['gambar_user'] = $namaFile;
        }

        DB::table('users')->where('id', $id)->update($payload);

        return redirect('/user')->with('warning', 'Data berhasil diupdate.');
    }

    public function editProfile(UpdateUserRequest $request, $id)
    {
        $validated = $request->validated();
        $targetUserId = (int) $id;

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'id_level' => $validated['id_level'],
            'password' => Hash::make($validated['password']),
        ];

        if ($request->hasFile('gambar_user')) {
            $file = $request->file('gambar_user');
            $namaFile = $file->hashName();
            $file->move(public_path('assets/img/user/'), $namaFile);

            $payload['gambar_user'] = $namaFile;
        }

        DB::table('users')->where('id', $targetUserId)->update($payload);

        return redirect()->back()->with('warning', 'Data berhasil diupdate.');
    }
}
