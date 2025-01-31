<?php

namespace Database\Seeders;

use App\Models\Listt;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ListtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Listt::factory()->count(15)->create();
    }
}
