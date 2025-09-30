<?php

namespace App\Imports;

use App\Models\Peserta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class PesertaImport implements SkipsEmptyRows, ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, WithUpserts, WithValidation
{
    public function model(array $row)
    {
        $name = trim($row['name'] ?? '');
        if (empty($name) || strlen($name) < 3) {
            dd('Debug: Row with invalid name', $row); // Temporary debug

            return null; // Skip this row
        }

        $tgl = null;
        if (isset($row['tanggal_lahir']) && $row['tanggal_lahir'] !== '') {
            $tgl = is_numeric($row['tanggal_lahir'])
                ? Carbon::instance(ExcelDate::excelToDateTimeObject($row['tanggal_lahir']))->toDateString()
                : Carbon::parse($row['tanggal_lahir'])->toDateString();
        }

        $user = null;
        if (! empty($row['email'])) {
            $user = User::firstOrCreate(
                ['email' => $row['email']],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                ]
            );
            $user->assignRole('peserta');
        }

        $jenisKelamin = trim($row['jenis_kelamin'] ?? '');
        if (empty($jenisKelamin) || ! in_array($jenisKelamin, ['Laki-laki', 'Perempuan'])) {

            return null;
        }

        $peserta = new Peserta([
            'id' => Str::uuid(),
            'user_id' => $user ? $user->id : null,
            'name' => $name,
            'email' => $row['email'] ?? null,
            'no_hp' => isset($row['no_hp']) ? preg_replace('/\s+|-/', '', (string) $row['no_hp']) : null,
            'nik' => isset($row['nik']) ? preg_replace('/\D/', '', (string) $row['nik']) : null,
            'tanggal_lahir' => $tgl,
            'jenis_kelamin' => $jenisKelamin,
            'pendidikan_terakhir' => $row['pendidikan_terakhir'] ?? null,
            'alamat' => $row['alamat'] ?? null,
        ]);

        // Log before save for debugging

        $peserta->save();

        return $peserta;
    }

    public function rules(): array
    {
        return [
            '*.name' => ['required', 'string', 'min:3'],
            '*.email' => ['nullable', 'email'],
            '*.nik' => ['nullable', 'digits_between:8,20'],
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

    // upsert by nik since it's unique in the database
    public function uniqueBy()
    {
        return ['nik'];
    }
}
