<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\PesertaImport;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PesertaController extends Controller
{
     public function index(): View
     {
         $pesertas = Peserta::with('user')->paginate(10);

         return view('admin.peserta', [
             'user' => Auth::user(),
             'pesertas' => $pesertas,
         ]);
     }

    public function store(Request $request): RedirectResponse
    {
        // Normalize jenis_kelamin to title case
        $jenisKelamin = ucwords(strtolower($request->jenis_kelamin));
        $request->merge(['jenis_kelamin' => $jenisKelamin]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:30', 'unique:pesertas,nik'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'no_hp' => ['required', 'string', 'max:20'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'pendidikan_terakhir' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->assignRole('peserta');

            $user->peserta()->create([
                'id' => Str::uuid(),
                'name' => $validated['name'],
                'nik' => $validated['nik'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
                'alamat' => $validated['alamat'],
                'no_hp' => $validated['no_hp'],
                'email' => $validated['email'],
            ]);
        });

        return redirect()->route('admin.peserta.index')->with('status', 'Peserta baru berhasil ditambahkan.');
    }

    public function downloadTemplate(): BinaryFileResponse
    {
        $path = resource_path('templates/peserta_template.xlsx');
        abort_unless(file_exists($path), 404, 'Template file not found.');

        return response()->download($path, 'peserta_template.xlsx');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:20480'],
        ]);

        // Basic import (switch to queueImport for large files)
        Excel::import(new PesertaImport, $request->file('file'));

        return redirect()->route('admin.peserta.index')
            ->with('status', 'Import peserta berhasil diproses.');
    }

    public function destroy(Peserta $peserta): RedirectResponse
    {
        $peserta->delete();

        return redirect()->route('admin.peserta.index')->with('status', 'Peserta berhasil dihapus.');
    }
}
