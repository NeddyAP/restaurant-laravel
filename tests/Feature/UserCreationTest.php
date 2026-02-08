<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class UserCreationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create levels
        DB::table('tbl_levels')->insert([
            ['id_level' => 1, 'nama_level' => 'Admin', 'created_at' => now()],
            ['id_level' => 2, 'nama_level' => 'Waiter', 'created_at' => now()],
        ]);
    }

    public function test_vulnerability_missing_validation()
    {
        // Create an admin user to authenticate
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'id_level' => 1,
            'created_at' => now(),
        ]);

        $admin = User::find($adminId);

        // Attempt to create a user with invalid data (empty fields)
        // This should fail validation, but currently it might succeed or crash.
        // We expect a validation error after the fix.
        $response = $this->actingAs($admin)->post('/user/tambah', [
            'name' => '', // Invalid
            'email' => '', // Invalid
            'id_level' => '', // Invalid
            'password' => '', // Invalid
        ]);

        // If validation was present, we would expect session errors.
        // Currently, it likely redirects with success or 500s.
        // I will assert that we have session errors for these fields.
        // This assertion will FAIL before I apply the fix.
        $response->assertSessionHasErrors(['name', 'email', 'id_level', 'password']);
    }

    public function test_vulnerability_duplicate_email()
    {
        // Create an admin user to authenticate
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'id_level' => 1,
            'created_at' => now(),
        ]);

        $admin = User::find($adminId);

        // Create another user
        DB::table('users')->insert([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => Hash::make('password'),
            'id_level' => 2,
            'created_at' => now(),
        ]);

        // Attempt to create a user with the same email
        $response = $this->actingAs($admin)->post('/user/tambah', [
            'name' => 'New User',
            'email' => 'existing@example.com', // Duplicate
            'id_level' => 2,
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_successful_user_creation()
    {
        // Create an admin user to authenticate
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'id_level' => 1,
            'created_at' => now(),
        ]);

        $admin = User::find($adminId);

        $response = $this->actingAs($admin)->post('/user/tambah', [
            'name' => 'Valid User',
            'email' => 'valid@example.com',
            'id_level' => 2,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/user');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'valid@example.com',
            'name' => 'Valid User'
        ]);
    }
}
