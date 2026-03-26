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