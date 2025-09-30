<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Jadwal extends Model
{
    /** @use HasFactory<\Database\Factories\JadwalFactory> */
    use HasFactory;

    protected $guarded = [];    

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function penjab(): BelongsTo
    {
        return $this->belongsTo(Penjab::class);
    }

    public function penjabs(): BelongsToMany
    {
        return $this->belongsToMany(Penjab::class, 'jadwal_penjab');
    }

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function tempat(): BelongsTo
    {
        return $this->belongsTo(Tempat::class);
    }

    public function mentors(): BelongsToMany
    {
        return $this->belongsToMany(Mentor::class, 'jadwal_mentor');
    }

    public function pesertas(): BelongsToMany
    {
        return $this->belongsToMany(Peserta::class, 'jadwal_peserta');
    }
}
