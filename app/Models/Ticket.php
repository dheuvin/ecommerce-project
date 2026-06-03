<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'status',
        'attachment'
    ];

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
