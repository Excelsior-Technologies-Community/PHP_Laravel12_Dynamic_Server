<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Server</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{background:#020617;color:#fff;font-family:system-ui,-apple-system,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
.card{background:#0f172a;border:1px solid #1e293b;border-radius:16px;padding:32px;width:100%;max-width:520px}
.card-hdr{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px}
.card-hdr h1{font-size:1.4rem;font-weight:900}
label{display:block;font-size:.82rem;font-weight:600;color:#94a3b8;margin-bottom:6px;margin-top:16px}
label:first-of-type{margin-top:0}
input,select{width:100%;background:#020617;border:1px solid #334155;border-radius:10px;padding:10px 14px;color:#fff;font-size:.9rem;outline:none}
input:focus,select:focus{border-color:#4f46e5}
select option{background:#0f172a}
.btn{display:inline-block;padding:10px 18px;border-radius:10px;font-weight:600;font-size:.9rem;cursor:pointer;border:none;color:#fff;transition:opacity .15s;text-decoration:none}
.btn:hover{opacity:.85}
.btn-yellow{background:#a16207}
.btn-slate{background:#334155}
.btn-submit{width:100%;margin-top:20px;padding:12px;font-size:1rem}
.errors{background:rgba(185,28,28,.15);border:1px solid rgba(185,28,28,.3);color:#f87171;padding:12px 16px;border-radius:10px;margin-bottom:16px;font-size:.85rem}
.errors li{margin-left:16px;margin-top:4px}
</style>
</head>
<body>
<div class="card">
    <div class="card-hdr">
        <h1>✏️ Edit Server</h1>
        <a href="{{ route('servers.index') }}" class="btn btn-slate">← Back</a>
    </div>
    @if($errors->any())
    <div class="errors"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif
    <form action="{{ route('servers.update', $server->id) }}" method="POST">
        @csrf @method('PUT')
        @include('servers._form')
        <button type="submit" class="btn btn-yellow btn-submit">Update Server</button>
    </form>
</div>
</body>
</html>
