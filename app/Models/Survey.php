<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    /** @use HasFactory<\Database\Factories\SurveyFactory> */
    use HasFactory;

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id')
            ->withTimestamps();
    }

    public function jawaban()
    {
        return $this->belongsTo(Jawaban::class,'jawaban_id')
            ->withTimestamps();
    }
}
