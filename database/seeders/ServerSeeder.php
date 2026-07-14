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
        Server::create(['name' => 'Local DB',       'host' => '127.0.0.1', 'database' => 'PHP_Laravel12_Dynamic_Server', 'username' => 'root', 'password' => null, 'tag' => 'Local']);
        Server::create(['name' => 'Production DB',   'host' => '192.168.1.10', 'database' => 'prod_db',   'username' => 'admin', 'password' => 'secret', 'tag' => 'Production']);
        Server::create(['name' => 'Staging DB',      'host' => '192.168.1.20', 'database' => 'staging_db','username' => 'admin', 'password' => 'secret', 'tag' => 'Staging']);
    }
}
