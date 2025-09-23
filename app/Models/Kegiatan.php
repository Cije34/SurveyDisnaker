<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    /** @use HasFactory<\Database\Factories\KegiatanFactory> */
    use HasFactory;

    public function tahunKegiatan()
    {
        return $this->belongsTo(TahunKegiatan::class, 'tahun_kegiatan_kegiatan')
            ->withTimestamps();
    }

    public function survey()
    {
        return $this->hasMany(Survey::class, 'kegiatan_id');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class,'kegiatan_id')
            ->withTimestamps();
    }
}
