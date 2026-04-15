<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Masakans extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbl_masakans')->insert([
            [
                'id_masakan' => '1',
                'gambar_masakan' => 'sate-ayam.jpg',
                'nama_masakan' => 'Sate',
                'harga' => '10000',
                'status_masakan' => 'Makanan',
            ],
            [
                'id_masakan' => '2',
                'gambar_masakan' => 'soto.jpg',
                'nama_masakan' => 'Soto',
                'harga' => '10000',
                'status_masakan' => 'Makanan',
            ],
            [
                'id_masakan' => '3',
                'gambar_masakan' => 'nasi-goreng.jpg',
                'nama_masakan' => 'Nasi Goreng',
                'harga' => '15000',
                'status_masakan' => 'Makanan',
            ],
            [
                'id_masakan' => '4',
                'gambar_masakan' => 'bakso.jpg',
                'nama_masakan' => 'Bakso',
                'harga' => '14000',
                'status_masakan' => 'Makanan',
            ],
            [
                'id_masakan' => '5',
                'gambar_masakan' => 'mie-ayam.jpg',
                'nama_masakan' => 'Mie Ayam',
                'harga' => '15000',
                'status_masakan' => 'Makanan',
            ],
            [
                'id_masakan' => '6',
                'gambar_masakan' => 'jus-apple.jpg',
                'nama_masakan' => 'Jus Apple',
                'harga' => '8000',
                'status_masakan' => 'Minuman',
            ],
        ]);
    }
}
