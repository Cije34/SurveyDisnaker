<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    /** @use HasFactory<\Database\Factories\MentorFactory> */
    use HasFactory;


    public function jadwals()
    {
        return $this->belongsToMany(Kegiatan::class, 'jadwal_mentor')
            ->withTimestamps();
    }
}
