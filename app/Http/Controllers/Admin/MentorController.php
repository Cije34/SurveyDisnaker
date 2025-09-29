<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mentor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
        ]);

        Mentor::create($validated);

        return redirect()->route('admin.mentor.index')->with('status', 'Mentor baru berhasil ditambahkan.');
    }
}
