<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->isProduction()) {
            return;
        }

        DB::table('partners')->insert([
            'name'      => 'Company 1',
            'type'      => 1,
            'endpoint'  => 'https://partner1.example.net/api/v1/orders'
        ]);

        DB::table('partners')->insert([
            'name'      => 'Company 2',
            'type'      => 2,
            'endpoint'  => 'https://partner2.example.net',
            'username'  => 'fake_sftp',
            'password'  => 'test123'
        ]);

    }

}
