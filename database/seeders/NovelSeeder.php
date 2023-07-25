<?php

namespace Database\Seeders;

use App\Models\novel;
use Illuminate\Database\Seeder;

class NovelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        novel::factory(50)->create();
    }
}
