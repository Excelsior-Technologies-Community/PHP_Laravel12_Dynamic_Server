<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DynamicDatabaseService
{
    public function connect($server)
    {
        Config::set('database.connections.dynamic', [
            'driver'    => 'mysql',
            'host'      => $server->host,
            'database'  => $server->database,
            'username'  => $server->username,
            'password'  => $server->password,
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => true,
            'port'      => 3306,
        ]);

        DB::purge('dynamic');

        return DB::connection('dynamic');
    }

    public function testConnection($server): bool
    {
        try {
            $conn = $this->connect($server);
            $conn->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
