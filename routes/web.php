<?php

use App\Http\Controllers\Detail_OrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\MasakanController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('dashboard');
// });



Auth::routes();
Route::get('', [HomeController::class, 'index'])->name('home');
Route::get('transaksi', [HomeController::class, 'transaksi'])->name('transaksi');


Route::group(['middleware' => 'auth'], function () {

    Route::get('masakan', [MasakanController::class, 'index'])->name('masakan')->middleware('auth');
    Route::get('masakan/hapus/{id_masakan}', [MasakanController::class, 'hapus'])->name('masakanHapus');
    Route::post('masakan/tambah', [MasakanController::class, 'tambah'])->name('masakanTambah');
    Route::post('masakan/edit/{id_masakan}', [MasakanController::class, 'edit'])->name('masakanEdit');
});

Route::group(['middleware' => 'auth'], function () {

    Route::get('level', [LevelController::class, 'index'])->name('level')->middleware('auth');
    Route::get('level/hapus/{id_level}', [LevelController::class, 'hapus'])->name('levelHapus');
    Route::post('level/tambah', [LevelController::class, 'tambah'])->name('levelTambah');
    Route::post('level/edit/{id_level}', [LevelController::class, 'edit'])->name('levelEdit');
});

Route::group(['middleware' => 'auth'], function () {

    Route::get('user', [UserController::class, 'index'])->name('user')->middleware('auth');
    Route::get('user/hapus/{id}', [UserController::class, 'hapus'])->name('userHapus');
    Route::post('user/tambah', [UserController::class, 'tambah'])->name('userTambah');
    Route::post('user/edit/{id}', [UserController::class, 'edit'])->name('userEdit');
    
    // User Profile
    Route::post('user/edit2/{id}', [UserController::class, 'editProfile'])->name('userEditProfile');
    Route::get('user/{id}', [UserController::class, 'indexProfile'])->name('userProfile')->middleware('auth');
});

Route::group(['middleware' => 'auth'], function () {

    Route::get('order', [OrderController::class, 'index'])->name('order')->middleware('auth');
    Route::get('order/hapus/{id_order}', [OrderController::class, 'hapus'])->name('orderHapus');
    Route::post('order/tambah', [OrderController::class, 'tambah'])->name('orderTambah');
    Route::post('order/edit/{id_order}', [OrderController::class, 'edit'])->name('orderEdit');
    Route::post('order/selesai/{id_order}', [OrderController::class, 'selesai'])->name('orderSelesai');
});

Route::group(['middleware' => 'auth'], function () {

    Route::get('order/detail/{id_order}', [Detail_OrderController::class, 'index'])->name('detail_order')->middleware('auth');
    Route::get('order/detail/{id_order}/hapus/{id_detail_order}', [Detail_OrderController::class, 'hapus'])->name('detail_orderHapus');
    Route::post('order/detail/{id_order}/tambah', [Detail_OrderController::class, 'tambah'])->name('detail_orderTambah');
    Route::post('order/detail/{id_order}/edit/{id_detail_order}', [Detail_OrderController::class, 'edit'])->name('detail_orderEdit');
});
