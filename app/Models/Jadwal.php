<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jadwal extends Model
{
    /** @use HasFactory<\Database\Factories\JadwalFactory> */
    use HasFactory;

    public function penjab()
    {
        return $this->belongsTo(Penjab::class, 'penjab_id')
            ->withTimestamps();
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id')
            ->withTimestamps();
    }

    public function tempat()
    {
        return $this->belongsTo(Tempat::class, 'tempat_id')
            ->withTimestamps();
    }

    public function mentor()
    {
        return $this->belongsToMany(Mentor::class, 'jadwal_mentor')
            ->withTimestamps();
    }

    public function peserta()
    {
        return $this->belongsToMany(Peserta::class, 'jadwal_peserta')
            ->withTimestamps();
    }

}
