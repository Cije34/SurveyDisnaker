<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjab extends Model
{
    /** @use HasFactory<\Database\Factories\PenjabFactory> */
    use HasFactory;

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'penjab_id')
            ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
