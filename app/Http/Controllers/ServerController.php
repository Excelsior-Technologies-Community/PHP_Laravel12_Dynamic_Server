<?php

namespace App\Http\Controllers;

use App\Models\ConnectionLog;
use App\Models\Server;
use App\Services\DynamicDatabaseService;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    // ─── Dashboard / Index ───────────────────────────────────────────────────

    public function index(Request $request)
    {
        $search = $request->search;
        $tag    = $request->tag;

        $servers = Server::when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('host', 'like', "%{$search}%")
                ->orWhere('database', 'like', "%{$search}%"))
            ->when($tag, fn($q) => $q->where('tag', $tag))
            ->oldest()
            ->paginate(10);

        $totalServers     = Server::count();
        $successCount     = ConnectionLog::where('status', 'success')->count();
        $failedCount      = ConnectionLog::where('status', 'failed')->count();
        $tags             = Server::distinct()->pluck('tag');

        return view('servers.index', compact(
            'servers', 'search', 'tag', 'totalServers', 'successCount', 'failedCount', 'tags'
        ));
    }

    // ─── Create / Store ──────────────────────────────────────────────────────

    public function create()
    {
        return view('servers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'host'     => 'required|string|max:255',
            'database' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:255',
            'tag'      => 'required|string|max:50',
        ]);

        Server::create($request->all());

        return redirect()->route('servers.index')->with('success', 'Server added successfully.');
    }

    // ─── Edit / Update ───────────────────────────────────────────────────────

    public function edit($id)
    {
        $server = Server::findOrFail($id);
        return view('servers.edit', compact('server'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'host'     => 'required|string|max:255',
            'database' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:255',
            'tag'      => 'required|string|max:50',
        ]);

        $server = Server::findOrFail($id);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $server->update($data);

        return redirect()->route('servers.index')->with('success', 'Server updated successfully.');
    }

    // ─── Delete ──────────────────────────────────────────────────────────────

    public function destroy($id)
    {
        Server::findOrFail($id)->delete();
        return redirect()->route('servers.index')->with('success', 'Server deleted successfully.');
    }

    // ─── Connect + Table List ────────────────────────────────────────────────

    public function connect($id, DynamicDatabaseService $service)
    {
        $server = Server::findOrFail($id);

        try {
            $db     = $service->connect($server);
            $tables = $db->select('SHOW TABLES');

            ConnectionLog::create(['server_id' => $id, 'status' => 'success', 'message' => 'Connected successfully.']);

            return view('servers.connect', compact('server', 'tables'));
        } catch (\Exception $e) {
            ConnectionLog::create(['server_id' => $id, 'status' => 'failed', 'message' => $e->getMessage()]);

            return redirect()->route('servers.index')->with('error', 'Connection failed: ' . $e->getMessage());
        }
    }

    // ─── Table Data Viewer ───────────────────────────────────────────────────

    public function tableData(Request $request, $id, DynamicDatabaseService $service)
    {
        $server    = Server::findOrFail($id);
        $tableName = $request->table;

        try {
            $db      = $service->connect($server);
            $perPage = 15;
            $page    = $request->get('page', 1);
            $offset  = ($page - 1) * $perPage;

            $total   = $db->table($tableName)->count();
            $rows    = $db->table($tableName)->offset($offset)->limit($perPage)->get();
            $columns = $rows->isNotEmpty() ? array_keys((array) $rows->first()) : [];

            $lastPage = (int) ceil($total / $perPage);

            return view('servers.table-data', compact('server', 'tableName', 'rows', 'columns', 'total', 'page', 'lastPage', 'perPage'));
        } catch (\Exception $e) {
            return redirect()->route('servers.connect', $id)->with('error', 'Could not load table: ' . $e->getMessage());
        }
    }

    // ─── Connection Status (AJAX) ─────────────────────────────────────────────

    public function status($id, DynamicDatabaseService $service)
    {
        $server = Server::findOrFail($id);
        $online = $service->testConnection($server);
        return response()->json(['online' => $online]);
    }

    // ─── Export Table Data ───────────────────────────────────────────────────

    public function export(Request $request, $id, DynamicDatabaseService $service)
    {
        $server    = Server::findOrFail($id);
        $tableName = $request->table;

        $db   = $service->connect($server);
        $rows = $db->table($tableName)->get();

        if ($rows->isEmpty()) {
            return back()->with('error', 'No data to export.');
        }

        $columns  = array_keys((array) $rows->first());
        $filename = $tableName . '_export_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($rows, $columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);
            foreach ($rows as $row) {
                fputcsv($handle, (array) $row);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─── Query Runner ────────────────────────────────────────────────────────

    public function queryRunner($id)
    {
        $server = Server::findOrFail($id);
        return view('servers.query-runner', compact('server'));
    }

    public function runQuery(Request $request, $id, DynamicDatabaseService $service)
    {
        $request->validate(['query' => 'required|string']);

        $server = Server::findOrFail($id);
        $query  = trim($request->query);

        // Only SELECT allowed
        if (!preg_match('/^SELECT\s/i', $query)) {
            return back()->withInput()->with('error', 'Only SELECT queries are allowed.');
        }

        try {
            $db      = $service->connect($server);
            $results = $db->select($query);
            $columns = $results ? array_keys((array) $results[0]) : [];

            return view('servers.query-runner', compact('server', 'results', 'columns', 'query'));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Query error: ' . $e->getMessage());
        }
    }

    // ─── Connection Logs ─────────────────────────────────────────────────────

    public function logs()
    {
        $logs = ConnectionLog::with('server')->latest()->paginate(20);
        return view('servers.logs', compact('logs'));
    }
}
