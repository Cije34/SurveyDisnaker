<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    /** @use HasFactory<\Database\Factories\SurveyFactory> */
    use HasFactory;

    public const TYPE_CHOICE = 'choice';
    public const TYPE_TEXT = 'text';

    protected $fillable = [
        'kegiatan_id',
        'type',
        'pertanyaan',
        'is_active',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function jawabans(): HasMany
    {
        return $this->hasMany(Jawaban::class);
    }

    public function isChoice(): bool
    {
        return $this->type === self::TYPE_CHOICE;
    }

    public function isText(): bool
    {
        return $this->type === self::TYPE_TEXT;
    }
}
