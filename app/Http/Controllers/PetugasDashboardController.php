<?php

namespace App\Http\Controllers;

use App\Models\Loan;

class PetugasDashboardController extends Controller
{
    public function index()
    {
        $pengajuan = Loan::with(['book', 'user', 'bookReturn'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.petugas', compact('pengajuan'));
    }
}
