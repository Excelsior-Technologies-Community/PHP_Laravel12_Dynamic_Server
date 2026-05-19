<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Server Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background:
                radial-gradient(circle at top left, #312e81, transparent 25%),
                radial-gradient(circle at bottom right, #0f172a, transparent 35%),
                #020617;
        }

        .glass {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .table-hover:hover {
            background: rgba(99, 102, 241, 0.08);
        }

        ::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background: #4f46e5;
            border-radius: 10px;
        }
    </style>
</head>

<body class="text-white min-h-screen">

    <div class="container mx-auto px-5 py-6">

        {{-- Small Header --}}
        <div class="glass rounded-2xl px-6 py-4 mb-6 shadow-2xl">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>
                    <h1 class="text-2xl md:text-3xl font-black tracking-tight">
                        🚀 Dynamic Server Dashboard
                    </h1>

                    <p class="text-slate-400 text-sm mt-1">
                        Laravel 12 Dynamic Database Manager
                    </p>
                </div>

                <a href="{{ route('servers.create') }}"
                    class="bg-indigo-600 hover:bg-indigo-700 transition duration-300 px-5 py-3 rounded-xl font-semibold shadow-lg shadow-indigo-500/20">
                    + Add Server
                </a>

            </div>

        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

            <div class="glass rounded-2xl p-5 shadow-xl">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-slate-400 text-xs uppercase tracking-widest">
                            Total Servers
                        </p>

                        <h2 class="text-4xl font-black mt-2 text-indigo-400">
                            {{ $totalServers }}
                        </h2>
                    </div>

                    <div class="text-5xl opacity-20">
                        🖥️
                    </div>

                </div>

            </div>

            <div class="glass rounded-2xl p-5 shadow-xl">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-slate-400 text-xs uppercase tracking-widest">
                            Active Connections
                        </p>

                        <h2 class="text-4xl font-black mt-2 text-green-400">
                            {{ $totalServers }}
                        </h2>
                    </div>

                    <div class="text-5xl opacity-20">
                        ⚡
                    </div>

                </div>

            </div>

            <div class="glass rounded-2xl p-5 shadow-xl">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-slate-400 text-xs uppercase tracking-widest">
                            Status
                        </p>

                        <h2 class="text-3xl font-black mt-2 text-pink-400">
                            ONLINE
                        </h2>
                    </div>

                    <div class="text-5xl opacity-20">
                        🔥
                    </div>

                </div>

            </div>

        </div>

        {{-- Search --}}
        <div class="glass rounded-2xl p-5 mb-6 shadow-xl">

            <form method="GET" action="{{ route('servers.index') }}">

                <div class="flex flex-col md:flex-row gap-4">

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="🔍 Search server name, host or database..."
                        class="w-full bg-slate-950/70 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">

                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 transition duration-300 px-6 py-3 rounded-xl font-semibold">
                        Search
                    </button>

                </div>

            </form>

        </div>

        {{-- Table --}}
        <div class="glass rounded-2xl overflow-hidden shadow-2xl">

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-slate-900/80 text-slate-300 uppercase text-sm">

                        <tr>
                            <th class="px-6 py-4 text-left">#</th>
                            <th class="px-6 py-4 text-left">Server</th>
                            <th class="px-6 py-4 text-left">Host</th>
                            <th class="px-6 py-4 text-left">Database</th>
                            <th class="px-6 py-4 text-left">Username</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($servers as $server)

                            <tr class="border-t border-slate-800 table-hover transition duration-300">

                                <td class="px-6 py-4 font-semibold text-slate-400">
                                    #{{ $server->id }}
                                </td>

                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="h-11 w-11 rounded-xl bg-indigo-500/20 flex items-center justify-center text-xl">
                                            🖥️
                                        </div>

                                        <div>
                                            <h2 class="font-bold text-indigo-400">
                                                {{ $server->name }}
                                            </h2>

                                            <p class="text-xs text-slate-500">
                                                Dynamic Database Server
                                            </p>
                                        </div>

                                    </div>

                                </td>

                                <td class="px-6 py-4 text-slate-300">
                                    {{ $server->host }}
                                </td>

                                <td class="px-6 py-4 text-slate-300">
                                    {{ $server->database }}
                                </td>

                                <td class="px-6 py-4 text-slate-300">
                                    {{ $server->username }}
                                </td>

                                <td class="px-6 py-4 text-center">

                                    <span
                                        class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-xs font-bold border border-green-500/20">
                                        ● ACTIVE
                                    </span>

                                </td>

                                <td class="px-6 py-4">

                                    <div class="flex justify-center gap-3">

                                        <a href="{{ route('servers.connect', $server->id) }}"
                                            class="bg-green-600 hover:bg-green-700 transition duration-300 px-4 py-2 rounded-lg text-sm font-semibold shadow-lg">
                                            Connect
                                        </a>

                                        <form action="{{ route('servers.destroy', $server->id) }}" method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button onclick="return confirm('Delete this server?')"
                                                class="bg-red-600 hover:bg-red-700 transition duration-300 px-4 py-2 rounded-lg text-sm font-semibold shadow-lg">
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center py-14 text-slate-500 text-lg">
                                    🚫 No servers found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $servers->links() }}
        </div>

    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                background: '#0f172a',
                color: '#fff',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                background: '#0f172a',
                color: '#fff',
            });
        </script>
    @endif

</body>

</html>