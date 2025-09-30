<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tempat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class TempatController extends Controller
{
    public function index(): View
    {
        $tempats = Tempat::paginate(10);

        return view('admin.tempat', [
            'user' => Auth::user(),
            'tempats' => $tempats,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tempats,name'],
            'alamat' => ['nullable', 'string', 'max:255'],
        ]);

        Tempat::create($validated);

        return redirect()->route('admin.tempat.index')->with('status', 'Tempat baru berhasil ditambahkan.');
    }

    public function update(Request $request, Tempat $tempat): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tempats,name,' . $tempat->id],
            'alamat' => ['nullable', 'string', 'max:255'],
        ]);

        $tempat->update($validated);

        return redirect()->route('admin.tempat.index')->with('status', 'Tempat berhasil diperbarui.');
    }

    public function destroy(Tempat $tempat): RedirectResponse
    {
        try {
            Log::info('Menghapus tempat: ', ['id' => $tempat->id, 'name' => $tempat->name]);

            $tempat->delete();

            Log::info('Tempat berhasil dihapus: ', ['id' => $tempat->id]);

            return redirect()->route('admin.tempat.index')->with('status', 'Tempat berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error menghapus tempat: ', ['id' => $tempat->id, 'error' => $e->getMessage()]);

            return redirect()->route('admin.tempat.index')->with('error', 'Gagal menghapus tempat: ' . $e->getMessage());
        }
    }
}
