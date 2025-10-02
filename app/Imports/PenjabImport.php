<?php

namespace App\Imports;

use App\Models\Penjab;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class PenjabImport implements SkipsEmptyRows, ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, WithUpserts, WithValidation
{
    public function model(array $row)
    {
        $name = trim($row['name'] ?? '');
        if (empty($name) || strlen($name) < 3) {
            Log::warning('Skipping row due to invalid name: '.json_encode($row));
            return null;
        }

        $jenisKelamin = trim($row['jenis_kelamin'] ?? '');
        if (empty($jenisKelamin) || ! in_array($jenisKelamin, ['Laki-laki', 'Perempuan'])) {
            Log::warning('Skipping row due to invalid jenis_kelamin: '.json_encode($row));
            return null;
        }

        $email = strtolower(trim((string) ($row['email'] ?? '')));
        if (empty($email)) {
            Log::warning('Skipping row due to missing email: '.json_encode($row));
            return null;
        }

        $jabatan = trim((string) ($row['jabatan'] ?? ''));
        if ($jabatan === '') {
            Log::warning('Skipping row due to missing jabatan: '.json_encode($row));
            return null;
        }

        $noHp = isset($row['no_hp']) ? preg_replace('/\s+|-/', '', (string) $row['no_hp']) : '';
        if ($noHp === '') {
            Log::warning('Skipping row due to missing no_hp: '.json_encode($row));
            return null;
        }

        // Create user first
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('password'),
            ]
        );

        if ($user->wasRecentlyCreated) {
            $user->assignRole('admin');
        } elseif ($user->name !== $name) {
            $user->forceFill(['name' => $name])->save();
        }

        $emailConflict = Penjab::query()
            ->where('email', $email)
            ->where('user_id', '!=', $user->id)
            ->exists();

        if ($emailConflict) {
            Log::warning('Skipping row due to email conflict: '.json_encode($row));
            return null;
        }

        $timestamp = now();

        return new Penjab([
            'user_id' => $user->id,
            'name' => $name,
            'jabatan' => $jabatan,
            'email' => $email,
            'no_hp' => $noHp,
            'jenis_kelamin' => $jenisKelamin,
            'alamat' => $row['alamat'] ?? null,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name' => ['required', 'string', 'min:3'],
            '*.email' => ['required', 'email'],
            '*.jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            '*.jabatan' => ['required', 'string'],
            '*.no_hp' => ['required'],
        ];
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function uniqueBy()
    {
        return ['user_id'];
    }

    public function upsertColumns()
    {
        return [
            'name',
            'jabatan',
            'email',
            'no_hp',
            'jenis_kelamin',
            'alamat',
            'updated_at',
        ];
    }
}
