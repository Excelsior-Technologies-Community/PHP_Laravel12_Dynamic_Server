<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConnectionLog extends Model
{
    protected $fillable = ['server_id', 'status', 'message'];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
