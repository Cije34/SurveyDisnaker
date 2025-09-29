<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penjab;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
}
