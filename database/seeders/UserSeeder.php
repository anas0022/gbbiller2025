<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example user
        DB::table('user_models')->insert([
            'username'     => 'superadmin',
            'name'         => 'Administrator',
            'email'        => 'admin@example.com',
            'mobile'       => '0',
            'password'     => Hash::make('password123'), // hashed password
            'role'         => 'superadmin',
            'user_type'    => '0',
            'store_id'     => 0,
            'mobile_code'  => '0',
            'plan'         => '0',
            'created_by'   => '0',
            'biller_code'  => 'BILL000',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

      
    }
}
