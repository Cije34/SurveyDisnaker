<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PesertaController extends Controller
{
    public function index(): View
    {
        $pesertas = Peserta::with('user')->paginate(30);

        return view('admin.peserta', [
            'user' => Auth::user(),
            'pesertas' => $pesertas,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
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
}
