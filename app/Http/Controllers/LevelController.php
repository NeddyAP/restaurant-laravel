<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $levels = Level::latest()->paginate(5);
        return view('level.index', compact('levels'))
            ->with('no', (request()->input('page', 1) - 1) * 5)
            ->with('no2', (request()->input('page', 1) - 1) * 5);
    }

    public function tambah(Request $request)
    {
        DB::table('tbl_levels')->insert([
            'nama_level' => $request->nama_level,
            'created_at' => now()
        ]);

        return redirect('/level')->with('success', 'Data berhasil dibuat.');
    }

    public function hapus($id_level)
    {
        DB::table('tbl_levels')->where('id_level', $id_level)->delete();

        return redirect('/level')->with('danger', 'Data berhasil dihapus.');
    }
    public function edit(Request $request)
    {
        DB::table('tbl_levels')->where('id_level', $request->id_level)->update([
            'nama_level' => $request->nama_level,
        ]);

        return redirect('/level')->with('warning', 'Data berhasil diupdate.');
    }
}
