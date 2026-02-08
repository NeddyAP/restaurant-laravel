<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Detail_OrderController extends Controller
{
    public function index($id_order)
    {
        $masakans = DB::table('tbl_masakans')->latest()->get();
        $no_meja = DB::table('tbl_orders')->select('no_meja')->where('tbl_orders.id_order', $id_order)->first();
        $detail_orders = DB::table('tbl_detail_order')
            ->latest()
            ->join('tbl_orders', 'tbl_orders.id_order', '=', 'tbl_detail_order.id_order')
            ->join('tbl_masakans', 'tbl_masakans.id_masakan', '=', 'tbl_detail_order.id_masakan')
            ->join('users', 'users.id', '=', 'tbl_orders.id_user')
            ->where('tbl_detail_order.id_order', $id_order)
            ->select('tbl_detail_order.*', 'tbl_orders.id_user', 'tbl_orders.no_meja','tbl_orders.tanggal', 'tbl_masakans.nama_masakan', 'users.name', 'tbl_masakans.harga')
            ->paginate(5);
         
        $harga = $detail_orders->sum('harga');
        DB::table('tbl_orders')->where('id_order', $id_order)->update([
            'total_harga' => $harga
        ]);

        if(DB::table('tbl_detail_order')->where('tbl_detail_order.id_order', $id_order)->count() == 0){
            
            $data = 0;
        }

        else{
            
            $data = 1;
        }
        return view('order.detail_order.index', compact('detail_orders', 'masakans', 'id_order', 'data', 'no_meja'))
            ->with('no', (request()->input('page', 1) - 1) * 5);

        // return json_encode($detail_orders);

    }

    public function tambah(Request $request ,$id_order)
    {
        DB::table('tbl_detail_order')->insert([
            'id_order' => $request->id_order,
            'id_masakan' => $request->id_masakan,
            'keterangan' => $request->keterangan,
            'status_detail_order' => $request->status_detail_order,
            'created_at' => now()
        ]);
        return redirect()->action(
            'Detail_OrderController@index', ['id_order' => $id_order]
        )
        ->with('success', 'Data berhasil dibuat.');
        // return view('/order/detail/')->with('success', 'Data berhasil dibuat.');
    }

    public function hapus($id_order, $id_detail_order)
    {
        DB::table('tbl_detail_order')->where('id_detail_order', $id_detail_order)->delete();
        
        
        return redirect()->action(
            'Detail_OrderController@index', ['id_order' => $id_order]
        )
        ->with('danger', 'Data berhasil dihapus.');
        // return redirect('/order/detail/')->with('danger', 'Data berhasil dihapus.');
    }
    public function edit(Request $request,$id_order)
    {
        DB::table('tbl_detail_order')->where('id_order', $request->id_order)->update([
            'id_masakan' => $request->id_masakan,
            'keterangan' => $request->keterangan,
            'status_detail_order' => $request->status_detail_order,
        ]);


        return redirect()->action(
            'Detail_OrderController@index', ['id_order' => $id_order]
        )
        ->with('danger', 'Data berhasil dihapus.');

        // return redirect('/order')->with('warning', 'Data berhasil diupdate.');
    }
}