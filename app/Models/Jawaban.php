<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    /** @use HasFactory<\Database\Factories\JawabanFactory> */
    use HasFactory;

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function jawaban()
    {
        return $this->belongsTo(Jawaban::class);
    }
}
