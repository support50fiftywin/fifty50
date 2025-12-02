<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Bronze', 'price' => 10, 'entries' => 50],
            ['name' => 'Silver', 'price' => 25, 'entries' => 200],
            ['name' => 'Gold', 'price' => 50, 'entries' => 450],
            ['name' => 'Diamond', 'price' => 100, 'entries' => 1000],
        ];
        Package::insert($data);
    }
}
