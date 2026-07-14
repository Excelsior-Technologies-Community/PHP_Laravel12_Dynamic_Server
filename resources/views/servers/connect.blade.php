<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $server->name }} - Connected</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{background:#020617;color:#fff;font-family:system-ui,-apple-system,sans-serif;min-height:100vh}
a{text-decoration:none;color:inherit}
.wrap{max-width:1100px;margin:0 auto;padding:24px 20px}
.card{background:#0f172a;border:1px solid #1e293b;border-radius:16px;padding:20px;margin-bottom:16px}
.hdr{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:16px}
.hdr h1{font-size:1.5rem;font-weight:900}
.hdr p{color:#4ade80;font-size:.85rem;margin-top:4px}
.btn-row{display:flex;gap:8px}
.btn{display:inline-block;padding:8px 16px;border-radius:10px;font-weight:600;font-size:.85rem;cursor:pointer;border:none;color:#fff;transition:opacity .15s}
.btn:hover{opacity:.85}
.btn-blue{background:#1d4ed8}
.btn-slate{background:#334155}
.details-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
@media(max-width:640px){.details-grid{grid-template-columns:repeat(2,1fr)}}
.detail-label{font-size:.7rem;color:#475569;text-transform:uppercase;letter-spacing:.06em}
.detail-val{font-size:.95rem;font-weight:600;margin-top:4px;color:#e2e8f0}
.tbl-wrap{background:#0f172a;border:1px solid #1e293b;border-radius:16px;overflow:hidden}
.tbl-scroll{overflow-x:auto}
.tbl-hdr{padding:14px 20px;border-bottom:1px solid #1e293b;font-weight:700;color:#94a3b8}
table{width:100%;border-collapse:collapse;font-size:.875rem}
th{padding:10px 16px;text-align:left;color:#64748b;font-size:.7rem;text-transform:uppercase;background:#0a1628}
td{padding:10px 16px;border-top:1px solid #1e293b;color:#cbd5e1}
tr:hover td{background:rgba(99,102,241,.06)}
.tbl-name{color:#a5b4fc;font-weight:600}
.actions{display:flex;gap:6px}
.btn-sm{padding:5px 12px;font-size:.78rem;border-radius:8px}
.btn-indigo{background:#4338ca}
.btn-green{background:#15803d}
.empty{text-align:center;padding:40px;color:#475569}
</style>
</head>
<body>
<div class="wrap">

    <div class="card">
        <div class="hdr">
            <div>
                <h1>🔌 {{ $server->name }}</h1>
                <p>✅ Connected Successfully</p>
            </div>
            <div class="btn-row">
                <a href="{{ route('servers.query', $server->id) }}" class="btn btn-blue">⚡ Query Runner</a>
                <a href="{{ route('servers.index') }}" class="btn btn-slate">← Back</a>
            </div>
        </div>
        <div class="details-grid">
            <div><div class="detail-label">Host</div><div class="detail-val">{{ $server->host }}</div></div>
            <div><div class="detail-label">Database</div><div class="detail-val">{{ $server->database }}</div></div>
            <div><div class="detail-label">Username</div><div class="detail-val">{{ $server->username }}</div></div>
            <div><div class="detail-label">Tag</div><div class="detail-val" style="color:#818cf8">{{ $server->tag }}</div></div>
        </div>
    </div>

    <div class="tbl-wrap">
        <div class="tbl-hdr">🗄️ Tables <span style="color:#475569;font-weight:400;font-size:.85rem">({{ count($tables) }} found)</span></div>
        @if(count($tables) > 0)
        <div class="tbl-scroll">
            <table>
                <thead><tr><th>#</th><th>Table Name</th><th>Actions</th></tr></thead>
                <tbody>
                @foreach($tables as $i => $table)
                @php $tn = array_values((array)$table)[0]; @endphp
                <tr>
                    <td style="color:#475569">{{ $i+1 }}</td>
                    <td class="tbl-name">{{ $tn }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('servers.table-data', ['id'=>$server->id,'table'=>$tn]) }}" class="btn btn-indigo btn-sm">👁️ View</a>
                            <a href="{{ route('servers.export', ['id'=>$server->id,'table'=>$tn]) }}" class="btn btn-green btn-sm">📥 CSV</a>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty">🚫 No tables found.</div>
        @endif
    </div>

</div>
</body>
</html>
