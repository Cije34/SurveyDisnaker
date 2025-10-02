<?php

namespace App\Imports;

use App\Models\Mentor;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class MentorImport implements SkipsEmptyRows, ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, WithUpserts, WithValidation
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
        if ($email === '') {
            Log::warning('Skipping row due to missing email: '.json_encode($row));
            return null;
        }

        $noHp = isset($row['no_hp']) ? preg_replace('/\s+|-/', '', (string) $row['no_hp']) : '';
        if ($noHp === '') {
            Log::warning('Skipping row due to missing no_hp: '.json_encode($row));
            return null;
        }

        $timestamp = now();

        return new Mentor([
            'name' => $name,
            'email' => $email,
            'no_hp' => $noHp,
            'jenis_kelamin' => $jenisKelamin,
            'alamat' => $row['alamat'] ?? null,
            'materi' => $row['materi'] ?? null,
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
        return ['email'];
    }

    public function upsertColumns()
    {
        return [
            'name',
            'no_hp',
            'jenis_kelamin',
            'alamat',
            'materi',
            'updated_at',
        ];
    }
}
