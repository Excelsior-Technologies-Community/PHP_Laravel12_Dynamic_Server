<label>Server Name</label>
<input type="text" name="name" value="{{ old('name', $server->name ?? '') }}" placeholder="e.g. My Local Server" required>

<label>Host</label>
<input type="text" name="host" value="{{ old('host', $server->host ?? '') }}" placeholder="e.g. 127.0.0.1" required>

<label>Database Name</label>
<input type="text" name="database" value="{{ old('database', $server->database ?? '') }}" placeholder="e.g. my_database" required>

<label>Username</label>
<input type="text" name="username" value="{{ old('username', $server->username ?? '') }}" placeholder="e.g. root" required>

<label>Password <span style="color:#475569;font-weight:400">(leave empty to keep current)</span></label>
<input type="password" name="password" placeholder="Database password">

<label>Tag</label>
<select name="tag">
    @foreach(['Production','Staging','Development','Local','Testing'] as $t)
        <option value="{{ $t }}" {{ old('tag', $server->tag ?? 'Production') == $t ? 'selected' : '' }}>{{ $t }}</option>
    @endforeach
</select>
