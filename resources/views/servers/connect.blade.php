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