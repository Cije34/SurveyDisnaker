<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    /** @use HasFactory<\Database\Factories\PesertaFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Include 'id' because we write a UUID manually when creating via relation.
     */
    protected $fillable = [
        'id',
        'user_id',
        'name',
        'alamat',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'pendidikan_terakhir',
        'no_hp',
        'email',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    public function jadwals()
    {
        return $this->belongsToMany(Jadwal::class, 'jadwal_peserta')
            ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
