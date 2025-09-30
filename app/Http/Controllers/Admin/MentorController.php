<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\MentorImport;
use App\Models\Mentor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MentorController extends Controller
{
    public function index(): View
    {
        $mentors = Mentor::paginate(10);

        return view('admin.mentor', [
            'user' => Auth::user(),
            'mentors' => $mentors,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:mentors,name'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:mentors,email'],
            'no_hp' => ['required', 'string', 'max:20'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'alamat' => ['required', 'string', 'max:255'],
            'materi' => ['required', 'string', 'max:255'],
        ]);

        Mentor::create($validated);

        return redirect()->route('admin.mentor.index')->with('status', 'Mentor baru berhasil ditambahkan.');
    }

    public function update(Request $request, Mentor $mentor): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:mentors,name,' . $mentor->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:mentors,email,' . $mentor->id],
            'no_hp' => ['required', 'string', 'max:20'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'alamat' => ['required', 'string', 'max:255'],
            'materi' => ['required', 'string', 'max:255'],
        ]);

        $mentor->update($validated);

        return redirect()->route('admin.mentor.index')->with('status', 'Mentor berhasil diperbarui.');
    }

    public function downloadTemplate(): BinaryFileResponse
    {
        $path = resource_path('templates/mentor_template.xlsx');
        abort_unless(file_exists($path), 404, 'Template file not found.');

        return response()->download($path, 'mentor_template.xlsx');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:20480'],
        ]);

        Excel::import(new MentorImport, $request->file('file'));

        return redirect()->route('admin.mentor.index')
            ->with('status', 'Import mentor berhasil diproses.');
    }

    public function destroy(Mentor $mentor): RedirectResponse
    {
        try {
            \Log::info('Menghapus mentor: ', ['id' => $mentor->id, 'name' => $mentor->name]);

            $mentor->delete();

            \Log::info('Mentor berhasil dihapus: ', ['id' => $mentor->id]);

            return redirect()->route('admin.mentor.index')->with('status', 'Mentor berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Error menghapus mentor: ', ['id' => $mentor->id, 'error' => $e->getMessage()]);

            return redirect()->route('admin.mentor.index')->with('error', 'Gagal menghapus mentor: ' . $e->getMessage());
        }
    }
}
