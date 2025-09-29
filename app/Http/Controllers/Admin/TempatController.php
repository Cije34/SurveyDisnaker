<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tempat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        ]);

        Tempat::create($validated);

        return redirect()->route('admin.tempat.index')->with('status', 'Tempat baru berhasil ditambahkan.');
    }
}
