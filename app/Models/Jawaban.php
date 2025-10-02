<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    /** @use HasFactory<\Database\Factories\JawabanFactory> */
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'peserta_id',
        'survey_id',
        'jawaban',
        'tipe',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
