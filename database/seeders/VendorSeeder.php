<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vendors')->insert([
            'phone' => '1234567890',
            'address' => '123 Main St',
            'user_id' => User::create([
                'name' => 'Vendor User',
                'email' => 'vendor@example.com',
                'password' => bcrypt('password'),
                'phone' => '1234567890',
                'address' => '123 Main St',
                'role' => 'vendor',
            ])->id,
        ]);
    }
}
