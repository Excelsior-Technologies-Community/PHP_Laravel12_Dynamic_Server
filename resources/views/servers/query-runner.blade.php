<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Query Runner</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{background:#020617;color:#fff;font-family:system-ui,-apple-system,sans-serif;min-height:100vh}
a{text-decoration:none;color:inherit}
.wrap{max-width:1100px;margin:0 auto;padding:24px 20px}
.card{background:#0f172a;border:1px solid #1e293b;border-radius:16px;padding:20px;margin-bottom:16px}
.hdr{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:16px}
.hdr h1{font-size:1.5rem;font-weight:900}
.hdr p{color:#64748b;font-size:.85rem;margin-top:4px}
.btn{display:inline-block;padding:8px 16px;border-radius:10px;font-weight:600;font-size:.85rem;cursor:pointer;border:none;color:#fff;transition:opacity .15s}
.btn:hover{opacity:.85}
.btn-indigo{background:#4f46e5}
.btn-slate{background:#334155}
label{display:block;font-size:.82rem;font-weight:600;color:#94a3b8;margin-bottom:8px}
textarea{width:100%;background:#020617;border:1px solid #334155;border-radius:10px;padding:12px 14px;color:#4ade80;font-family:monospace;font-size:.9rem;outline:none;resize:vertical}
textarea:focus{border-color:#4f46e5}
.form-footer{display:flex;align-items:center;justify-content:space-between;margin-top:12px;flex-wrap:wrap;gap:8px}
.hint{font-size:.78rem;color:#475569}
.alert-error{background:rgba(185,28,28,.15);border:1px solid rgba(185,28,28,.3);color:#f87171;padding:10px 14px;border-radius:10px;margin-bottom:12px;font-size:.85rem}
.tbl-wrap{background:#0f172a;border:1px solid #1e293b;border-radius:16px;overflow:hidden}
.tbl-hdr{padding:14px 20px;border-bottom:1px solid #1e293b;font-weight:700;color:#94a3b8;font-size:.9rem}
.tbl-scroll{overflow-x:auto}
::-webkit-scrollbar{height:6px} ::-webkit-scrollbar-thumb{background:#4f46e5;border-radius:6px}
table{width:100%;border-collapse:collapse;font-size:.85rem}
th{padding:10px 14px;text-align:left;color:#64748b;font-size:.7rem;text-transform:uppercase;background:#0a1628;white-space:nowrap}
td{padding:9px 14px;border-top:1px solid #1e293b;color:#cbd5e1;white-space:nowrap}
tr:hover td{background:rgba(99,102,241,.06)}
.empty{text-align:center;padding:32px;color:#475569}
</style>
</head>
<body>
<div class="wrap">

    <div class="card">
        <div class="hdr">
            <div>
                <h1>⚡ Query Runner</h1>
                <p>{{ $server->name }} &bull; SELECT queries only</p>
            </div>
            <a href="{{ route('servers.connect', $server->id) }}" class="btn btn-slate">← Back</a>
        </div>

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('servers.run-query', $server->id) }}" method="POST">
            @csrf
            <label>SQL Query</label>
            <textarea name="query" rows="5" placeholder="SELECT * FROM users LIMIT 10;">{{ old('query', $query ?? '') }}</textarea>
            <div class="form-footer">
                <span class="hint">⚠️ Only SELECT statements allowed</span>
                <button type="submit" class="btn btn-indigo">▶ Run Query</button>
            </div>
        </form>
    </div>

    @isset($results)
    <div class="tbl-wrap">
        <div class="tbl-hdr">Results <span style="color:#475569;font-weight:400">({{ count($results) }} rows)</span></div>
        @if(count($results) > 0)
        <div class="tbl-scroll">
            <table>
                <thead><tr>@foreach($columns as $col)<th>{{ $col }}</th>@endforeach</tr></thead>
                <tbody>
                @foreach($results as $row)
                <tr>@foreach((array)$row as $val)<td>{{ is_null($val) ? '—' : $val }}</td>@endforeach</tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty">No results returned.</div>
        @endif
    </div>
    @endisset

</div>
</body>
</html>
