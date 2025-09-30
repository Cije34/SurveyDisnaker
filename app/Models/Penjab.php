<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjab extends Model
{
    /** @use HasFactory<\Database\Factories\PenjabFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'jabatan',
        'no_hp',
        'email',
        'alamat',
        'jenis_kelamin',
    ];

    public function jadwals()
    {
        return $this->belongsToMany(Jadwal::class, 'jadwal_penjab');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
