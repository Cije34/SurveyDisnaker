<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    /** @use HasFactory<\Database\Factories\MentorFactory> */
    use HasFactory;


    protected $fillable = [
        'name',
        'no_hp',
        'email',
        'alamat',
        'jenis_kelamin',
        'materi',
    ];

    public function jadwals()
    {
        return $this->belongsToMany(Jadwal::class, 'jadwal_mentor')
            ->withTimestamps();
    }
}
