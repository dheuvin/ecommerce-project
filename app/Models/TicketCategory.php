<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    protected $fillable = ['name','status'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

}
