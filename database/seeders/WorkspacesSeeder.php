<?php

namespace Database\Seeders;

use App\Models\Workspace;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkspacesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Workspace::factory()->count(5)->create();
    }
}
