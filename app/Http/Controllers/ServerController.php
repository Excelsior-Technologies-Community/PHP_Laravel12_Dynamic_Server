<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Services\DynamicDatabaseService;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $servers = Server::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('host', 'like', "%{$search}%")
                ->orWhere('database', 'like', "%{$search}%");
        })
            ->oldest()
            ->paginate(3);

        $totalServers = Server::count();

        return view('servers.index', compact(
            'servers',
            'search',
            'totalServers'
        ));
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

        return redirect()
            ->route('servers.index')
            ->with('success', 'Server added successfully.');
    }

    public function destroy($id)
    {
        $server = Server::findOrFail($id);

        $server->delete();

        return redirect()
            ->route('servers.index')
            ->with('success', 'Server deleted successfully.');
    }

    public function connect($id, DynamicDatabaseService $service)
    {
        try {
            $server = Server::findOrFail($id);

            $db = $service->connect($server);

            $tables = $db->select('SHOW TABLES');

            return view('servers.connect', compact('server', 'tables'));

        } catch (\Exception $e) {

            return redirect()
                ->route('servers.index')
                ->with('error', 'Connection failed!');
        }
    }
}