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