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

        $mentor = new Mentor([
            'name' => $name,
            'email' => $row['email'] ?? null,
            'no_hp' => isset($row['no_hp']) ? preg_replace('/\s+|-/', '', (string) $row['no_hp']) : null,
            'jenis_kelamin' => $jenisKelamin,
            'alamat' => $row['alamat'] ?? null,
            'materi' => $row['materi'] ?? null,
        ]);

        Log::info('Attempting to save Mentor: ', $mentor->toArray());
        $mentor->save();

        return $mentor;
    }

    public function rules(): array
    {
        return [
            '*.name' => ['required', 'string', 'min:3'],
            '*.email' => ['nullable', 'email'],
            '*.jenis_kelamin' => ['nullable', 'in:Laki-laki,Perempuan'],
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

    // upsert by name since it's unique in the database
    public function uniqueBy()
    {
        return ['name'];
    }
}