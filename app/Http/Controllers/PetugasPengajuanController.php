<?php

namespace App\Http\Controllers;

use App\Helpers\NotifHelper;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasPengajuanController extends Controller
{
    public function index()
    {
        $pending = Loan::with(['book', 'user'])
            ->where('pengajuan_status', 'pending')
            ->latest()
            ->get();

        $confirmed = Loan::with(['book', 'user'])
            ->where('confirmed_by', Auth::id())
            ->whereIn('pengajuan_status', ['disetujui', 'ditolak'])
            ->latest()
            ->get();

        return view('petugas.pengajuan', compact('pending', 'confirmed'));
    }

    public function tolak(Request $request, Loan $loan)
    {
        $request->validate(['rejection_reason' => 'required|string']);

        $loan->update([
            'pengajuan_status' => 'ditolak',
            'confirmed_by'     => Auth::id(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        NotifHelper::send($loan->user_id, 'loan_rejected',
            'Pengajuan peminjaman buku kamu ditolak.',
            ['judul' => $loan->book->title, 'alasan' => $request->rejection_reason]
        );

        return response()->json(['success' => true]);
    }

    public function pinjamkan(Request $request, Loan $loan)
    {
        $request->validate(['due_date' => 'required|date']);

        $loan->update([
            'pengajuan_status' => 'disetujui',
            'confirmed_by'     => Auth::id(),
            'due_date'         => $request->due_date,
        ]);

        // Kurangi stok buku
        $loan->book->decrement('stock');

        NotifHelper::send($loan->user_id, 'loan_approved',
            'Selamat! Peminjaman buku kamu telah dikonfirmasi.',
            ['judul' => $loan->book->title, 'due_date' => $request->due_date]
        );

        return response()->json(['success' => true]);
    }

    public function detail(Loan $loan)
    {
        $loan->load(['book', 'user']);
        return view('petugas.pengajuan.detail', compact('loan'));
    }

    public function cetak(Loan $loan)
    {
        $loan->load(['book', 'user']);
        return view('petugas.pengajuan.cetak', compact('loan'));
    }
}
