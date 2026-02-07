<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Masakan;
use App\Models\Order;
use App\Models\Transaksi;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::latest()->get();
        $transaksis = Transaksi::latest()->take(5)->get();
        $masakans = Masakan::latest()->take(5)->get();
        $orders = Order::join('users', 'users.id' ,'=','tbl_orders.id_user')
        ->select('tbl_orders.*','users.name')
        ->latest()
        ->take(5)
        ->get();
        return view('dashboard', compact('users', 'masakans', 'orders', 'transaksis'))
            ->with('i', (request()->input('page', 1) - 1) * 3)
            ->with('no', '1')
            ->with('no2', '1');
    }
    public function transaksi()
    {
        $transaksis = Transaksi::latest()->paginate(5);

        return view('transaksi.index', compact('transaksis'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
