<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Server;

class ServerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Server::create([
            'name' => 'Local DB',
            'host' => '127.0.0.1',
            'database' => 'dynamic_server_db',
            'username' => 'root',
            'password' => ''
        ]);
    }
}
