<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\User;

class DetailOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_with_no_detail_orders()
    {
        $this->withoutExceptionHandling(); // For debugging

        // Seed necessary data
        $levelId = DB::table('tbl_levels')->insertGetId(['nama_level' => 'admin', 'created_at' => now()]);
        $userId = DB::table('users')->insertGetId([
            'name' => 'Test User',
            'email' => 'test1@example.com',
            'password' => bcrypt('password'),
            'id_level' => $levelId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $orderId = DB::table('tbl_orders')->insertGetId([
            'id_user' => $userId,
            'no_meja' => '1',
            'tanggal' => now(),
            'total_harga' => 0,
            'keterangan' => 'Test Order',
            'status_order' => 'pending',
            'created_at' => now(),
        ]);

        // Authenticate
        $user = \App\Models\User::find($userId); // Assuming User model is correct for Auth
        // Or manually mock Auth
        $this->actingAs($user);

        // Call the route
        $response = $this->get(route('detail_order', ['id_order' => $orderId]));

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('data', 0);
    }

    public function test_index_with_detail_orders()
    {
        // Seed necessary data
        $levelId = DB::table('tbl_levels')->insertGetId(['nama_level' => 'admin', 'created_at' => now()]);
        $userId = DB::table('users')->insertGetId([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
            'id_level' => $levelId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $orderId = DB::table('tbl_orders')->insertGetId([
            'id_user' => $userId,
            'no_meja' => '1',
            'tanggal' => now(),
            'total_harga' => 0,
            'keterangan' => 'Test Order',
            'status_order' => 'pending',
            'created_at' => now(),
        ]);

        $masakanId = DB::table('tbl_masakans')->insertGetId([
            'nama_masakan' => 'Nasi Goreng',
            'harga' => 15000,
            'status_masakan' => 'tersedia',
            'created_at' => now(),
        ]);

        DB::table('tbl_detail_order')->insert([
            'id_order' => $orderId,
            'id_masakan' => $masakanId,
            'keterangan' => 'Pedas',
            'status_detail_order' => 'pending',
            'created_at' => now(),
        ]);

        // Authenticate
        $user = \App\Models\User::find($userId);
        $this->actingAs($user);

        // Call the route
        $response = $this->get(route('detail_order', ['id_order' => $orderId]));

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('data', 1);
    }
}
