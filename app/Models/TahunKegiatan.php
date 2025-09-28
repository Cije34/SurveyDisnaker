<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunKegiatan extends Model
{
    /** @use HasFactory<\Database\Factories\TahunKegiatanFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class);
    }
}
