<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Support\Facades\Auth;

class AnggotaTransaksiController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $aktif = Loan::with(['book', 'bookReturn'])
            ->where('user_id', $userId)
            ->where('status', '!=', 'dikembalikan')
            ->latest()->get();

        $dikembalikan = Loan::with(['book', 'bookReturn'])
            ->where('user_id', $userId)
            ->where('status', 'dikembalikan')
            ->latest()->get();

        return view('anggota.transaksi', compact('aktif', 'dikembalikan'));
    }

    public function kembalikan(Loan $loan)
    {
        // Pastikan loan milik user ini
        if ($loan->user_id !== Auth::id()) {
            return response()->json(['success' => false], 403);
        }

        $loan->update(['return_requested' => true]);

        return response()->json(['success' => true]);
    }
}
