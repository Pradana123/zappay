<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $username = env('DEFAULT_ADMIN_USERNAME');
        $email = env('DEFAULT_ADMIN_EMAIL');
        $password = env('DEFAULT_ADMIN_PASSWORD');
        $phone = env('DEFAULT_ADMIN_PHONE', '6280000000000');

        if (empty($username) || empty($email) || empty($password)) {
            throw new \RuntimeException('DEFAULT_ADMIN_USERNAME, DEFAULT_ADMIN_EMAIL, dan DEFAULT_ADMIN_PASSWORD wajib diatur sebelum menjalankan AdminUserSeeder.');
        }

        if (! DB::table('users')->where('email', $email)->exists()) {
            DB::table('users')->insert([
                'name'       => 'Administrator',
                'username'   => $username,
                'email'      => $email,
                'password'   => Hash::make($password),
                'api_key'    => 'BGRL-' . strtoupper(bin2hex(random_bytes(10))),
                'no_wa'      => $phone,
                'balance'    => 0,
                'role'       => 'Admin',
                'idgame'     => null,
                'servergame' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
