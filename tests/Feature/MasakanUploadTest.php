<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Masakan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MasakanUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_malicious_file_upload_is_prevented()
    {
        // 1. Authenticate as a user
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'id_level' => 1,
        ]);

        $this->actingAs($user);

        // 2. Create a Masakan record
        $masakanId = DB::table('tbl_masakans')->insertGetId([
            'nama_masakan' => 'Nasi Goreng',
            'harga' => '15000',
            'status_masakan' => 'tersedia',
            'gambar_masakan' => 'default.jpg',
            'created_at' => now(),
        ]);

        // 3. Create a fake file with a malicious name
        $maliciousFilename = 'shell.php';
        $file = UploadedFile::fake()->create($maliciousFilename, 100);

        // 4. Send POST request
        $response = $this->post(route('masakanEdit', ['id_masakan' => $masakanId]), [
            'id_masakan' => $masakanId,
            'nama_masakan' => 'Nasi Goreng Special',
            'harga' => '20000',
            'status_masakan' => 'tersedia',
            'gambar_masakan' => $file,
        ]);

        // 5. Assert redirection (success or validation error if we add validation)
        // If we add validation, it might redirect back with errors.
        // For now, let's assume successful upload but renamed file.
        // But if validation fails (e.g. mimes), we expect 302 and errors.

        // Check if file exists at the dangerous location
        $destinationPath = public_path('assets/img/masakan/' . $maliciousFilename);

        $this->assertFileDoesNotExist($destinationPath, "Vulnerability fixed: File should NOT be saved with original malicious name.");
    }

    public function test_valid_image_upload_is_handled_securely()
    {
        // 1. Authenticate as a user
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin2@example.com',
            'password' => bcrypt('password'),
            'id_level' => 1,
        ]);

        $this->actingAs($user);

        // 2. Create a Masakan record
        $masakanId = DB::table('tbl_masakans')->insertGetId([
            'nama_masakan' => 'Mie Goreng',
            'harga' => '12000',
            'status_masakan' => 'tersedia',
            'gambar_masakan' => 'default.jpg',
            'created_at' => now(),
        ]);

        // 3. Create a valid image file
        $originalFilename = 'delicious_food.jpg';
        $file = UploadedFile::fake()->image($originalFilename);

        // 4. Send POST request
        $response = $this->post(route('masakanEdit', ['id_masakan' => $masakanId]), [
            'id_masakan' => $masakanId,
            'nama_masakan' => 'Mie Goreng Special',
            'harga' => '18000',
            'status_masakan' => 'tersedia',
            'gambar_masakan' => $file,
        ]);

        $response->assertStatus(302);

        // 5. Verify the file is NOT saved with the original name
        $originalPath = public_path('assets/img/masakan/' . $originalFilename);
        $this->assertFileDoesNotExist($originalPath, "File should rely on generated unique name, not original name.");

        // 6. Verify the database is updated with a NEW filename
        $masakan = DB::table('tbl_masakans')->where('id_masakan', $masakanId)->first();
        $this->assertNotEquals($originalFilename, $masakan->gambar_masakan, "Database should store the new secure filename.");

        // 7. Verify the new file actually exists
        $newPath = public_path('assets/img/masakan/' . $masakan->gambar_masakan);
        $this->assertFileExists($newPath, "The file should exist at the new secure path.");

        // Cleanup
        if (file_exists($newPath)) {
            unlink($newPath);
        }
    }
}
