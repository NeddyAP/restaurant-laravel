<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

    public function tambah(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'id_level' => 'required|integer|exists:tbl_levels,id_level',
            'password' => 'required|string|min:8',
        ]);

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'id_level' => $request->id_level,
            'password' => Hash::make($request->password),
            'created_at' => now()
        ]);
        return redirect('/user')->with('success', 'Data berhasil dibuat.');
    }

    public function hapus($id)
    {
        DB::table('users')->where('id', $id)->delete();

        return redirect('/user')->with('danger', 'Data berhasil dihapus.');
    }
    public function edit(Request $request)
    {
        if ($request->hasFile('gambar_user')) {
            $namaFile = $request->file('gambar_user')->getClientOriginalName();
            $file->move(public_path('assets/img/user/'), $namaFile);
            DB::table('users')->where('id', $request->id)->update([
                'gambar_user' => $namaFile,
                'name' => $request->name,
                'email' => $request->email,
                'id_level' => $request->id_level,
                'password' => Hash::make($request->password),
            ]);
        }
        DB::table('users')->where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'id_level' => $request->id_level,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/user')->with('warning', 'Data berhasil diupdate.');
    }
    public function editProfile(Request $request, $id)
    {
        if ($request->hasFile('gambar_user')) {
            $namaFile = $request->file('gambar_user')->getClientOriginalName();
            $file->move(public_path('assets/img/user/'), $namaFile);
            DB::table('users')->where('id', $request->id)->update([
                'gambar_user' => $namaFile,
                'name' => $request->name,
                'email' => $request->email,
                'id_level' => $request->id_level,
                'password' => Hash::make($request->password),
            ]);
        }
        else{
            DB::table('users')->where('id', $request->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'id_level' => $request->id_level,
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->back()->with('warning', 'Data berhasil diupdate.');
    }
}