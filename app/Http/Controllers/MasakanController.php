<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Masakan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MasakanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $masakans = Masakan::latest()->paginate(5);
        return view('masakan.index', compact('masakans'))
            ->with('no', (request()->input('page', 1) - 1) * 5);
    }

    public function tambah(Request $request)
    {
        DB::table('tbl_masakans')->insert([
            'gambar_masakan' => 'default.jpg',
            'nama_masakan' => $request->nama_masakan,
            'harga' => 'Rp.' . $request->harga,
            'status_masakan' => $request->status_masakan,
            'created_at' => now(),
        ]);

        return redirect('/masakan')->with('success', 'Data berhasil dibuat.');
    }

    public function hapus($id_masakan)
    {
        DB::table('tbl_masakans')->where('id_masakan', $id_masakan)->delete();

        return redirect('/masakan')->with('danger', 'Data berhasil dihapus.');
    }
    public function edit(Request $request)
    {
        if ($request->hasFile('gambar_masakan')) {

        $request->validate([
            'gambar_masakan' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $file = $request->file('gambar_masakan');
        $namaFile = $file->hashName();
        $file->move(public_path('assets/img/masakan/'), $namaFile);
        DB::table('tbl_masakans')->where('id_masakan', $request->id_masakan)->update([
            'gambar_masakan' => $namaFile,
            'nama_masakan' => $request->nama_masakan,
            'harga' => 'Rp.' . $request->harga,
            'status_masakan' => $request->status_masakan
        ]);
        }
        else{
            DB::table('tbl_masakans')->where('id_masakan', $request->id_masakan)->update([
                'nama_masakan' => $request->nama_masakan,
                'harga' => 'Rp.' . $request->harga,
                'status_masakan' => $request->status_masakan
            ]);
        }
        return redirect('/masakan')->with('warning', 'Data berhasil diupdate.');
    }
}