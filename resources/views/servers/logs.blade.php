<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connection Logs</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{background:#020617;color:#fff;font-family:system-ui,-apple-system,sans-serif;min-height:100vh}
a{text-decoration:none;color:inherit}
.wrap{max-width:1100px;margin:0 auto;padding:24px 20px}
.hdr{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:16px}
.hdr h1{font-size:1.5rem;font-weight:900}
.hdr p{color:#64748b;font-size:.85rem;margin-top:4px}
.btn{display:inline-block;padding:8px 16px;border-radius:10px;font-weight:600;font-size:.85rem;cursor:pointer;border:none;color:#fff;transition:opacity .15s}
.btn:hover{opacity:.85}
.btn-slate{background:#334155}
.tbl-wrap{background:#0f172a;border:1px solid #1e293b;border-radius:16px;overflow:hidden}
.tbl-scroll{overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:.875rem}
th{padding:11px 16px;text-align:left;color:#64748b;font-size:.7rem;text-transform:uppercase;background:#0a1628}
td{padding:11px 16px;border-top:1px solid #1e293b;color:#cbd5e1;vertical-align:middle}
tr:hover td{background:rgba(99,102,241,.06)}
.badge{display:inline-block;padding:3px 10px;border-radius:999px;font-size:.72rem;font-weight:700;border:1px solid}
.badge-ok{background:rgba(21,128,61,.15);color:#4ade80;border-color:rgba(21,128,61,.3)}
.badge-fail{background:rgba(185,28,28,.15);color:#f87171;border-color:rgba(185,28,28,.3)}
.srv-name{font-weight:700;color:#818cf8}
.srv-host{font-size:.75rem;color:#475569;margin-top:2px}
.msg{max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#64748b;font-size:.82rem}
.empty{text-align:center;padding:48px;color:#475569}
.pager{padding:12px 16px;border-top:1px solid #1e293b}
</style>
</head>
<body>
<div class="wrap">

    <div class="hdr">
        <div>
            <h1>📋 Connection Logs</h1>
            <p>History of all server connection attempts</p>
        </div>
        <a href="{{ route('servers.index') }}" class="btn btn-slate">← Back</a>
    </div>

    <div class="tbl-wrap">
        <div class="tbl-scroll">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Server</th>
                        <th>Status</th>
                        <th>Message</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="color:#475569">#{{ $log->id }}</td>
                    <td>
                        <div class="srv-name">{{ $log->server->name ?? 'Deleted' }}</div>
                        <div class="srv-host">{{ $log->server->host ?? '' }}</div>
                    </td>
                    <td>
                        @if($log->status === 'success')
                            <span class="badge badge-ok">✅ Success</span>
                        @else
                            <span class="badge badge-fail">❌ Failed</span>
                        @endif
                    </td>
                    <td><div class="msg" title="{{ $log->message }}">{{ $log->message }}</div></td>
                    <td style="white-space:nowrap;color:#475569;font-size:.82rem">{{ $log->created_at->format('d M Y, h:i A') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="empty">🚫 No logs found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pager">{{ $logs->links() }}</div>
    </div>

</div>
</body>
</html>
