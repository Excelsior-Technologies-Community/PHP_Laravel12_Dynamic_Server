# PHP_Laravel12_Dynamic_Server

## Introduction

PHP_Laravel12_Dynamic_Server is a Laravel 12-based project that demonstrates dynamic database management and multi-server connectivity at runtime. Unlike traditional Laravel projects with a single fixed database connection, this project allows you to add multiple database servers and interact with them dynamically.

Key features include:

- Add multiple servers (MySQL) dynamically through a web interface
- Test connection to any server before using it
- View tables and structure of any connected database
- Dynamically switch connections without modifying .env or Laravel config
- Ideal for SaaS, multi-tenant applications, and projects requiring runtime DB flexibility

---

## Project Overview

This project provides a scalable system to manage and interact with multiple MySQL servers from a single Laravel application.

The system consists of the following components:

### 1) Server Management
- Add, view, and manage server configurations (host, database, username, password)
- Supports optional (nullable) passwords for local or unsecured environments

### 2) Dynamic Database Service
- Custom `DynamicDatabaseService` enables runtime database connections
- Dynamically connects to selected servers without modifying `.env`
- Retrieves tables and data from connected databases

### 3) Blade Templates (UI)
- `index.blade.php` – Displays all configured servers
- `create.blade.php` – Form to add a new server
- `connect.blade.php` – Displays server details and database tables

### 4) Seeder
- `ServerSeeder` inserts sample server data
- `DatabaseSeeder` registers and executes seeders

### 5) Routing & Controllers
- RESTful routes for server operations
- `ServerController` handles validation, storage, and dynamic DB interaction

---

## Technologies Used

- Laravel 12 (PHP 8 compatible)
- MySQL for database servers
- Tailwind CSS for responsive UI
- Custom service for runtime DB connections

---

## Step 1: Create Laravel 12 Project

Run the official Laravel command:

```bash
composer create-project laravel/laravel PHP_Laravel12_Dynamic_Server "12.*"
cd PHP_Laravel12_Dynamic_Server
```

---

## Step 2: Configure Database

Open .env file:

```.env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dynamic_server_db
DB_USERNAME=root
DB_PASSWORD=
```

Run default migrations:

```bash
php artisan migrate
```

---

## Step 3: Create Model & Migration Table

Run:

```bash
php artisan make:model Server -m
```


### Migration Table

Now open migration file:

`database/migrations/xxxx_create_servers_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('host');
            $table->string('database');
            $table->string('username');
            $table->string('password')->nullable(); // allow empty passwords
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
```

Run migration:

```bash
php artisan migrate
```

### Server Model

Open:

`app/Models/Server.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $fillable = [
        'name',
        'host',
        'database',
        'username',
        'password'
    ];
}
```
---

## Step 4: Dynamic Database Connection Service

Laravel does NOT provide built-in dynamic switching easily — so we create a custom service.

Create folder:

```
app/Services/
```

Create file:

`app/Services/DynamicDatabaseService.php`

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DynamicDatabaseService
{
    public function connect($server)
    {
        Config::set('database.connections.dynamic', [
            'driver' => 'mysql',
            'host' => $server->host,
            'database' => $server->database,
            'username' => $server->username,
            'password' => $server->password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
        ]);

        DB::purge('dynamic');

        return DB::connection('dynamic');
    }
}
```

---

## Step 5: Create Controller

Run:

```bash
php artisan make:controller ServerController
```

Open:

`app/Http/Controllers/ServerController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Services\DynamicDatabaseService;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::all();
        return view('servers.index', compact('servers'));
    }

    public function create()
    {
        return view('servers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'database' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:255',
        ]);

        Server::create($request->all());
        return redirect()->route('servers.index')->with('success', 'Server added successfully.');
    }

    public function connect($id, DynamicDatabaseService $service)
    {
        try {
            $server = Server::findOrFail($id);
            $db = $service->connect($server);
            $tables = $db->select('SHOW TABLES');

            return view('servers.connect', compact('server', 'tables'));
        } catch (\Exception $e) {
            return redirect()->route('servers.index')
                ->with('error', 'Connection failed: ' . $e->getMessage());
        }
    }
}
```

---

## Step 6: Blade Files 

### index.blade.php

File: `resources/views/servers/index.blade.php`

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">

<div class="container mx-auto mt-10 px-4">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-extrabold text-gray-800">Servers</h1>
        <a href="{{ route('servers.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded shadow transition duration-200">
           + Add Server
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 mb-4 rounded shadow">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-3 mb-4 rounded shadow">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow rounded-lg overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="text-left px-6 py-3 text-gray-600 uppercase tracking-wider">Name</th>
                    <th class="text-left px-6 py-3 text-gray-600 uppercase tracking-wider">Host</th>
                    <th class="text-left px-6 py-3 text-gray-600 uppercase tracking-wider">Database</th>
                    <th class="text-left px-6 py-3 text-gray-600 uppercase tracking-wider">Username</th>
                    <th class="text-left px-6 py-3 text-gray-600 uppercase tracking-wider">Password</th>
                    <th class="text-center px-6 py-3 text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($servers as $server)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 text-gray-700">{{ $server->id }}</td>
                    <td class="px-6 py-4 text-gray-800 font-medium">{{ $server->name }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $server->host }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $server->database }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $server->username }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $server->password ?? 'No Password' }}</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('servers.connect', $server->id) }}" 
                           class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow transition duration-200">
                           Test Connection
                        </a>
                    </td>
                </tr>
                @endforeach
                @if($servers->isEmpty())
                <tr>
                    <td colspan="7" class="text-center px-6 py-4 text-gray-500">No servers found. Add a new server to get started.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
```

### create.blade.php

File: `resources/views/servers/create.blade.php`

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Server</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">

<div class="container mx-auto mt-10 px-4">
    <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-8">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Add New Server</h1>
            <a href="{{ route('servers.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow transition duration-200">
               Back
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 mb-6 rounded shadow">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('servers.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block mb-1 font-semibold text-gray-700">Server Name</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                       class="w-full border-gray-300 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none" 
                       placeholder="Enter server name" required>
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700">Host</label>
                <input type="text" name="host" value="{{ old('host') }}" 
                       class="w-full border-gray-300 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none" 
                       placeholder="e.g., 127.0.0.1" required>
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700">Database Name</label>
                <input type="text" name="database" value="{{ old('database') }}" 
                       class="w-full border-gray-300 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none" 
                       placeholder="Enter database name" required>
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" 
                       class="w-full border-gray-300 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none" 
                       placeholder="Database username" required>
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700">Password (leave empty if none) </label>
                <input type="password" name="password" value="{{ old('password') }}" 
                       class="w-full border-gray-300 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none" 
                       placeholder="Database password">
            </div>

            <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg shadow-lg transition duration-200">
                Add Server
            </button>
        </form>
    </div>
</div>

</body>
</html>
```

### connect.blade.php

File: `resources/views/servers/connect.blade.php`

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Connection</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">

<div class="container mx-auto mt-10 px-4">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-extrabold text-gray-800">Server: {{ $server->name }}</h1>
        <a href="{{ route('servers.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-5 py-2 rounded shadow transition duration-200">
           Back
        </a>
    </div>

    <div class="bg-white p-6 rounded shadow mb-6 space-y-2">
        <h2 class="text-xl font-semibold">Server Details</h2>
        <p><strong>Host:</strong> {{ $server->host }}</p>
        <p><strong>Database:</strong> {{ $server->database }}</p>
        <p><strong>Username:</strong> {{ $server->username }}</p>
        <p><strong>Password:</strong> {{ $server->password ?? 'No Password' }}</p>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-3">Tables</h2>

        @if(count($tables) > 0)
            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-2 text-left text-gray-600 uppercase">Table Name</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($tables as $table)
                        @php
                            $tableArray = (array) $table;
                            $tableName = array_values($tableArray)[0];
                        @endphp
                        <tr>
                            <td class="px-6 py-2 text-gray-700">{{ $tableName }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-red-500">No tables found in this database.</p>
        @endif
    </div>
</div>

</body>
</html>
```

---

## Step 7: Insert Server Data (Using Seeder)

### Create Seeder

Run:

```bash
php artisan make:seeder ServerSeeder
```

Open:

`database/seeders/ServerSeeder.php`

```php
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
```

---

### Register Seeder

Open:

`database/seeders/DatabaseSeeder.php`

```php
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
```

### Run Seeder

```bash
php artisan db:seed
```

Note: Ensure the database `dynamic_server_db` exists in your MySQL before running the seeder.

---

## Step 8: Define Routes

Open:

`routes/web.php`

```php
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ServerController;


Route::get('/servers', [ServerController::class, 'index'])->name('servers.index');
Route::get('/servers/create', [ServerController::class, 'create'])->name('servers.create');
Route::post('/servers', [ServerController::class, 'store'])->name('servers.store');
Route::get('/servers/connect/{id}', [ServerController::class, 'connect'])->name('servers.connect');

Route::get('/', function () {
    return view('welcome');
});
```

## Step 9: Test Project

Start server:

```bash
php artisan serve
```
Open browser:

```bash
http://127.0.0.1:8000/servers
```

---

## Output

<img src="screenshots/Screenshot 2026-03-26 155401.png" width="1000">

<img src="screenshots/Screenshot 2026-03-26 155415.png" width="1000">

<img src="screenshots/Screenshot 2026-03-26 155530.png" width="1000">

---

## Project Structure

```
PHP_Laravel12_Dynamic_Server/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── ServerController.php
│   │   └── Middleware/...
│   ├── Models/
│   │   └── Server.php
│   ├── Services/
│   │   └── DynamicDatabaseService.php   <-- Handles DB connection & fetching tables
│   └── ...
│
├── database/
│   ├── migrations/
│   │   └── 2026_03_26_000000_create_servers_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php           <-- Calls ServerSeeder
│       └── ServerSeeder.php             <-- Adds sample server data
│
├── resources/
│   ├── views/
│   │   └── servers/
│   │       ├── index.blade.php         <-- List all servers
│   │       ├── create.blade.php        <-- Add new server
│   │       └── connect.blade.php       <-- Server details + tables
│   └── ...
│
├── routes/
│   └── web.php                           <-- Routes for servers
│
├── public/
│   └── index.php
│
├── .env                                  <-- DB config
├── composer.json
├── artisan
└── ...
```

---

Your PHP_Laravel12_Dynamic_Server Project is now ready!




