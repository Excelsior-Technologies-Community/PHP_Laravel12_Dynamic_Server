<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dynamic Server Dashboard</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{background:#020617;color:#fff;font-family:system-ui,-apple-system,sans-serif;min-height:100vh}
a{text-decoration:none;color:inherit}
.wrap{max-width:1280px;margin:0 auto;padding:24px 20px}

/* cards */
.card{background:#0f172a;border:1px solid #1e293b;border-radius:16px;padding:20px}
.card-sm{background:#0f172a;border:1px solid #1e293b;border-radius:12px;padding:16px}

/* header */
.hdr{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px}
.hdr h1{font-size:1.6rem;font-weight:900;letter-spacing:-.5px}
.hdr p{color:#64748b;font-size:.85rem;margin-top:4px}
.btn-row{display:flex;gap:10px;flex-wrap:wrap}

/* buttons */
.btn{display:inline-block;padding:9px 18px;border-radius:10px;font-weight:600;font-size:.85rem;cursor:pointer;border:none;color:#fff;transition:opacity .15s}
.btn:hover{opacity:.85}
.btn-indigo{background:#4f46e5}
.btn-slate{background:#334155}
.btn-green{background:#15803d}
.btn-blue{background:#1d4ed8}
.btn-yellow{background:#a16207}
.btn-red{background:#b91c1c}
.btn-sm{padding:6px 12px;font-size:.78rem;border-radius:8px}

/* stats */
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px}
@media(max-width:640px){.stats{grid-template-columns:repeat(2,1fr)}}
.stat-label{color:#64748b;font-size:.7rem;text-transform:uppercase;letter-spacing:.08em}
.stat-val{font-size:2.2rem;font-weight:900;margin-top:6px}

/* search */
.search-row{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px}
.search-row input,.search-row select{background:#0f172a;border:1px solid #334155;border-radius:10px;padding:9px 14px;color:#fff;font-size:.9rem;outline:none;flex:1;min-width:180px}
.search-row input:focus,.search-row select:focus{border-color:#4f46e5}
.search-row select option{background:#0f172a}

/* table */
.tbl-wrap{background:#0f172a;border:1px solid #1e293b;border-radius:16px;overflow:hidden}
.tbl-scroll{overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:.875rem}
thead{background:#0a1628}
th{padding:11px 16px;text-align:left;color:#94a3b8;font-size:.7rem;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap}
td{padding:11px 16px;border-top:1px solid #1e293b;vertical-align:middle}
tr:hover td{background:rgba(99,102,241,.06)}
.server-name{font-weight:700;color:#818cf8}
.server-user{font-size:.75rem;color:#475569;margin-top:2px}
.actions{display:flex;gap:6px;flex-wrap:wrap;justify-content:center}

/* tag badges */
.tag{display:inline-block;padding:2px 10px;border-radius:999px;font-size:.72rem;font-weight:700;border:1px solid}
.tag-red{background:rgba(185,28,28,.15);color:#f87171;border-color:rgba(185,28,28,.3)}
.tag-yellow{background:rgba(161,98,7,.15);color:#fbbf24;border-color:rgba(161,98,7,.3)}
.tag-blue{background:rgba(29,78,216,.15);color:#60a5fa;border-color:rgba(29,78,216,.3)}
.tag-purple{background:rgba(109,40,217,.15);color:#c084fc;border-color:rgba(109,40,217,.3)}
.tag-pink{background:rgba(157,23,77,.15);color:#f472b6;border-color:rgba(157,23,77,.3)}
.tag-slate{background:rgba(51,65,85,.3);color:#94a3b8;border-color:rgba(51,65,85,.5)}

/* status */
.status-dot{font-size:.78rem;font-weight:700;cursor:pointer;padding:4px 10px;border-radius:999px;border:1px solid #334155;background:#1e293b;display:inline-block}
.status-online{color:#4ade80;border-color:rgba(74,222,128,.3);background:rgba(74,222,128,.08)}
.status-offline{color:#f87171;border-color:rgba(248,113,113,.3);background:rgba(248,113,113,.08)}

/* pagination */
.pagination{margin-top:16px}
.pagination nav{display:flex;gap:6px;flex-wrap:wrap}
.pagination .page-link{background:#0f172a;border:1px solid #1e293b;color:#94a3b8;padding:6px 12px;border-radius:8px;font-size:.85rem}
.pagination .page-link:hover{border-color:#4f46e5;color:#fff}
.pagination .active .page-link{background:#4f46e5;border-color:#4f46e5;color:#fff}

/* alert */
.alert{padding:12px 16px;border-radius:10px;margin-bottom:16px;font-size:.9rem}
.alert-success{background:rgba(21,128,61,.15);border:1px solid rgba(21,128,61,.3);color:#4ade80}
.alert-error{background:rgba(185,28,28,.15);border:1px solid rgba(185,28,28,.3);color:#f87171}

/* empty */
.empty{text-align:center;padding:48px;color:#475569;font-size:1rem}
</style>
</head>
<body>
<div class="wrap">

    {{-- Header --}}
    <div class="hdr">
        <div>
            <h1>🚀 Dynamic Server Dashboard</h1>
            <p>Laravel 12 Dynamic Database Manager</p>
        </div>
        <div class="btn-row">
            <a href="{{ route('servers.logs') }}" class="btn btn-slate">📋 Logs</a>
            <a href="{{ route('servers.create') }}" class="btn btn-indigo">+ Add Server</a>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">❌ {{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div class="stats">
        <div class="card-sm">
            <div class="stat-label">Total Servers</div>
            <div class="stat-val" style="color:#818cf8">{{ $totalServers }}</div>
        </div>
        <div class="card-sm">
            <div class="stat-label">Successful</div>
            <div class="stat-val" style="color:#4ade80">{{ $successCount }}</div>
        </div>
        <div class="card-sm">
            <div class="stat-label">Failed</div>
            <div class="stat-val" style="color:#f87171">{{ $failedCount }}</div>
        </div>
        <div class="card-sm">
            <div class="stat-label">System</div>
            <div class="stat-val" style="color:#f472b6;font-size:1.4rem">ONLINE</div>
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('servers.index') }}">
        <div class="search-row">
            <input type="text" name="search" value="{{ $search }}" placeholder="🔍 Search name, host, database...">
            <select name="tag" style="flex:0;min-width:150px">
                <option value="">All Tags</option>
                @foreach($tags as $t)
                    <option value="{{ $t }}" {{ $tag == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-indigo" style="flex:0">Search</button>
            @if($search || $tag)
                <a href="{{ route('servers.index') }}" class="btn btn-slate" style="flex:0">Clear</a>
            @endif
        </div>
    </form>

    {{-- Table --}}
    <div class="tbl-wrap">
        <div class="tbl-scroll">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Server</th>
                        <th>Host</th>
                        <th>Database</th>
                        <th>Tag</th>
                        <th style="text-align:center">Status</th>
                        <th style="text-align:center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($servers as $server)
                @php
                    $tagMap = ['Production'=>'red','Staging'=>'yellow','Local'=>'blue','Development'=>'purple','Testing'=>'pink'];
                    $tc = $tagMap[$server->tag] ?? 'slate';
                @endphp
                <tr>
                    <td style="color:#475569">#{{ $server->id }}</td>
                    <td>
                        <div class="server-name">{{ $server->name }}</div>
                        <div class="server-user">{{ $server->username }}</div>
                    </td>
                    <td style="color:#cbd5e1">{{ $server->host }}</td>
                    <td style="color:#cbd5e1">{{ $server->database }}</td>
                    <td><span class="tag tag-{{ $tc }}">{{ $server->tag }}</span></td>
                    <td style="text-align:center">
                        <span class="status-dot" id="st-{{ $server->id }}" onclick="checkStatus({{ $server->id }}, this)" title="Click to check">⏳ Check</span>
                    </td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('servers.connect', $server->id) }}" class="btn btn-green btn-sm">🔌 Connect</a>
                            <a href="{{ route('servers.query', $server->id) }}" class="btn btn-blue btn-sm">⚡ Query</a>
                            <a href="{{ route('servers.edit', $server->id) }}" class="btn btn-yellow btn-sm">✏️ Edit</a>
                            <form action="{{ route('servers.destroy', $server->id) }}" method="POST" onsubmit="return confirm('Delete {{ addslashes($server->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-red btn-sm">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="empty">🚫 No servers found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination">{{ $servers->appends(request()->query())->links() }}</div>

</div>

<script>
function checkStatus(id, el) {
    el.textContent = '⏳';
    el.className = 'status-dot';
    fetch('/servers/' + id + '/status')
        .then(r => r.json())
        .then(d => {
            el.textContent = d.online ? '● ONLINE' : '● OFFLINE';
            el.className = 'status-dot ' + (d.online ? 'status-online' : 'status-offline');
        })
        .catch(() => { el.textContent = '? ERROR'; });
}
</script>
</body>
</html>
