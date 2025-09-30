<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\PenjabImport;
use App\Models\Penjab;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PenjabController extends Controller
{
    public function index(): View
    {
        $penjabs = Penjab::with('user')->paginate(10);

        return view('admin.penjab', [
            'user' => Auth::user(),
            'penjabs' => $penjabs,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:penjabs,email'],
            'no_hp' => ['required', 'string', 'max:20'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'alamat' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('admin'); // Assuming penjab has admin role

        Penjab::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'jabatan' => $validated['jabatan'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat' => $validated['alamat'],
        ]);

        return redirect()->route('admin.penjab.index')->with('status', 'Penanggung Jawab baru berhasil ditambahkan.');
    }

    public function update(Request $request, Penjab $penjab): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:penjabs,email,' . $penjab->id],
            'no_hp' => ['required', 'string', 'max:20'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'alamat' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Update user
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }
        $penjab->user->update($userData);

        // Update penjab
        $penjab->update([
            'name' => $validated['name'],
            'jabatan' => $validated['jabatan'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat' => $validated['alamat'],
        ]);

        return redirect()->route('admin.penjab.index')->with('status', 'Penanggung Jawab berhasil diperbarui.');
    }

    public function destroy(Penjab $penjab): RedirectResponse
    {
        try {
            \Log::info('Menghapus penjab: ', ['id' => $penjab->id, 'name' => $penjab->name]);

            // Hapus user terkait jika ada
            if ($penjab->user) {
                $penjab->user->delete();
            }

            $penjab->delete();

            \Log::info('Penjab berhasil dihapus: ', ['id' => $penjab->id]);

            return redirect()->route('admin.penjab.index')->with('status', 'Penanggung Jawab berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Error menghapus penjab: ', ['id' => $penjab->id, 'error' => $e->getMessage()]);

            return redirect()->route('admin.penjab.index')->with('error', 'Gagal menghapus penanggung jawab: ' . $e->getMessage());
        }
    }

    public function downloadTemplate(): BinaryFileResponse
    {
        $path = resource_path('templates/penjab_template.xlsx');
        abort_unless(file_exists($path), 404, 'Template file not found.');

        return response()->download($path, 'penjab_template.xlsx');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:20480'],
        ]);

        Excel::import(new PenjabImport, $request->file('file'));

        return redirect()->route('admin.penjab.index')
            ->with('status', 'Import penanggung jawab berhasil diproses.');
    }
}
