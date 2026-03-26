<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\ServerSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ServerSeeder::class,
        ]);
    }
}