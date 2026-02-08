<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'email' => 'owner@resto.com',
                'name' => 'owner',
                'id_level' => '3',
                'env_key' => 'OWNER_PASSWORD',
            ],
            [
                'email' => 'admin@resto.com',
                'name' => 'Administrator',
                'id_level' => '1',
                'env_key' => 'ADMIN_PASSWORD',
            ],
            [
                'email' => 'waiter@resto.com',
                'name' => 'waiter',
                'id_level' => '2',
                'env_key' => 'WAITER_PASSWORD',
            ],
            [
                'email' => 'kasir@resto.com',
                'name' => 'kasir',
                'id_level' => '2',
                'env_key' => 'CASHIER_PASSWORD',
            ],
            [
                'email' => 'pelanggan@resto.com',
                'name' => 'pelanggan',
                'id_level' => '4',
                'env_key' => 'CUSTOMER_PASSWORD',
            ],
        ];

        foreach ($users as $user) {
            $password = env($user['env_key']);

            if (!$password) {
                $password = Str::random(12);
                if ($this->command) {
                    $this->command->warn("{$user['env_key']} not set. Generated password for {$user['email']}: {$password}");
                }
            }

            DB::table('users')->insert([
                'email' => $user['email'],
                'password' => Hash::make($password),
                'name' => $user['name'],
                'id_level' => $user['id_level'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
