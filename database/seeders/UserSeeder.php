<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        //
        DB::table('users')->insert([
            'name' => 'Shawn',
            'email' => Str::random(10).'@gmail.com',
            'phone_number' => '14807034902',
            'is_admin' => 1,
            'password' => Hash::make('password')
        ]);

        DB::table('users')->insert([
            'name' => 'Jeremiah',
            'email' => Str::random(10).'@gmail.com',
            'is_admin' => 0,
            'phone_number' => '14807034902',
            'password' => Hash::make('password')
        ]);

        DB::table('users')->insert([
            'name' => 'Reid',
            'email' => Str::random(10).'@gmail.com',
            'is_admin' => 0,
            'phone_number' => '14807034902',
            'password' => Hash::make('password')
        ]);

        DB::table('users')->insert([
            'name' => 'Zach',
            'email' => Str::random(10).'@gmail.com',
            'is_admin' => 0,
            'phone_number' => '14807034902',
            'password' => Hash::make('password')
        ]);

        DB::table('users')->insert([
            'name' => 'Phillip',
            'email' => Str::random(10).'@gmail.com',
            'is_admin' => 0,
            'phone_number' => '14807034902',
            'password' => Hash::make('password')
        ]);

        DB::table('users')->insert([
            'name' => 'Elias',
            'email' => Str::random(10).'@gmail.com',
            'is_admin' => 0,
            'phone_number' => '14807034902',
            'password' => Hash::make('password')
        ]);
    }
}
