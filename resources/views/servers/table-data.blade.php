<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $tableName }}</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{background:#020617;color:#fff;font-family:system-ui,-apple-system,sans-serif;min-height:100vh}
a{text-decoration:none;color:inherit}
.wrap{max-width:1280px;margin:0 auto;padding:24px 20px}
.hdr{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:16px}
.hdr h1{font-size:1.5rem;font-weight:900}
.hdr p{color:#64748b;font-size:.85rem;margin-top:4px}
.btn-row{display:flex;gap:8px}
.btn{display:inline-block;padding:8px 16px;border-radius:10px;font-weight:600;font-size:.85rem;cursor:pointer;border:none;color:#fff;transition:opacity .15s}
.btn:hover{opacity:.85}
.btn-green{background:#15803d}
.btn-slate{background:#334155}
.tbl-wrap{background:#0f172a;border:1px solid #1e293b;border-radius:16px;overflow:hidden}
.tbl-scroll{overflow-x:auto}
::-webkit-scrollbar{height:6px} ::-webkit-scrollbar-thumb{background:#4f46e5;border-radius:6px}
table{width:100%;border-collapse:collapse;font-size:.85rem}
th{padding:10px 14px;text-align:left;color:#64748b;font-size:.7rem;text-transform:uppercase;background:#0a1628;white-space:nowrap}
td{padding:9px 14px;border-top:1px solid #1e293b;color:#cbd5e1;white-space:nowrap;max-width:220px;overflow:hidden;text-overflow:ellipsis}
tr:hover td{background:rgba(99,102,241,.06)}
.pager{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-top:1px solid #1e293b;font-size:.85rem;color:#64748b;flex-wrap:wrap;gap:8px}
.pager-btns{display:flex;gap:6px}
.pager-btn{padding:5px 14px;border-radius:8px;font-size:.82rem;font-weight:600;color:#fff}
.pager-prev{background:#334155}
.pager-next{background:#4f46e5}
.empty{text-align:center;padding:48px;color:#475569}
</style>
</head>
<body>
<div class="wrap">

    <div class="hdr">
        <div>
            <h1>🗄️ {{ $tableName }}</h1>
            <p>{{ $server->name }} &bull; {{ $total }} total rows</p>
        </div>
        <div class="btn-row">
            <a href="{{ route('servers.export', ['id'=>$server->id,'table'=>$tableName]) }}" class="btn btn-green">📥 Export CSV</a>
            <a href="{{ route('servers.connect', $server->id) }}" class="btn btn-slate">← Back</a>
        </div>
    </div>

    <div class="tbl-wrap">
        @if(count($rows) > 0)
        <div class="tbl-scroll">
            <table>
                <thead><tr>@foreach($columns as $col)<th>{{ $col }}</th>@endforeach</tr></thead>
                <tbody>
                @foreach($rows as $row)
                <tr>@foreach((array)$row as $val)<td title="{{ $val }}">{{ is_null($val) ? '—' : $val }}</td>@endforeach</tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="pager">
            <span>Showing {{ ($page-1)*$perPage+1 }}–{{ min($page*$perPage,$total) }} of {{ $total }}</span>
            <div class="pager-btns">
                @if($page > 1)
                    <a href="{{ request()->fullUrlWithQuery(['page'=>$page-1]) }}" class="pager-btn pager-prev">← Prev</a>
                @endif
                @if($page < $lastPage)
                    <a href="{{ request()->fullUrlWithQuery(['page'=>$page+1]) }}" class="pager-btn pager-next">Next →</a>
                @endif
            </div>
        </div>
        @else
        <div class="empty">🚫 No data found.</div>
        @endif
    </div>

</div>
</body>
</html>
