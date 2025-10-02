<?php

namespace App\Imports;

use App\Models\Peserta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class PesertaImport implements SkipsEmptyRows, SkipsOnFailure, ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, WithUpserts, WithValidation
{
    use SkipsFailures;

    public function model(array $row)
    {
        $name = trim((string) ($row['name'] ?? ''));
        if (strlen($name) < 3) {
            return null;
        }

        $email = strtolower(trim((string) ($row['email'] ?? '')));
        $nik = isset($row['nik']) ? preg_replace('/\D/', '', (string) $row['nik']) : null;

        if ($email === '' || empty($nik)) {
            return null;
        }

        $jenisKelamin = trim((string) ($row['jenis_kelamin'] ?? ''));
        if (! in_array($jenisKelamin, ['Laki-laki', 'Perempuan'], true)) {
            return null;
        }

        $tanggalLahir = null;
        if (isset($row['tanggal_lahir']) && $row['tanggal_lahir'] !== '') {
            $tanggalLahir = is_numeric($row['tanggal_lahir'])
                ? Carbon::instance(ExcelDate::excelToDateTimeObject($row['tanggal_lahir']))->toDateString()
                : Carbon::parse($row['tanggal_lahir'])->toDateString();
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('password'),
            ]
        );

        if ($user->wasRecentlyCreated) {
            $user->assignRole('peserta');
        } elseif ($user->name !== $name) {
            $user->forceFill(['name' => $name])->save();
        }

        $nikConflict = Peserta::query()
            ->where('nik', $nik)
            ->where('user_id', '!=', $user->id)
            ->exists();

        if ($nikConflict) {
            return null;
        }

        $existingPesertaId = Peserta::query()
            ->where('user_id', $user->id)
            ->value('id');

        $timestamp = now();

        $attributes = [
            'user_id' => $user->id,
            'name' => $name,
            'email' => $email,
            'no_hp' => isset($row['no_hp']) ? preg_replace('/\s+|-/', '', (string) $row['no_hp']) : null,
            'nik' => $nik,
            'tanggal_lahir' => $tanggalLahir,
            'jenis_kelamin' => $jenisKelamin,
            'pendidikan_terakhir' => $row['pendidikan_terakhir'] ?? null,
            'alamat' => $row['alamat'] ?? null,
            'updated_at' => $timestamp,
        ];

        if ($existingPesertaId) {
            $attributes['id'] = $existingPesertaId;
        } else {
            $attributes['id'] = (string) Str::uuid();
            $attributes['created_at'] = $timestamp;
        }

        return new Peserta($attributes);
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

    public function uniqueBy()
    {
        return ['user_id'];
    }

    public function upsertColumns()
    {
        return [
            'name',
            'email',
            'no_hp',
            'nik',
            'tanggal_lahir',
            'jenis_kelamin',
            'pendidikan_terakhir',
            'alamat',
            'updated_at',
        ];
    }
}
