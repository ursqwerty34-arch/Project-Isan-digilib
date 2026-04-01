<?php

namespace App\Http\Controllers;

use App\Models\BookReturn;
use App\Models\Loan;

class KepalaTransaksiController extends Controller
{
    public function index()
    {
        $peminjaman = Loan::with(['book', 'user'])
            ->whereIn('pengajuan_status', ['disetujui', 'ditolak'])
            ->latest()
            ->get();

        $pengembalian = BookReturn::with(['loan.book', 'loan.user'])
            ->latest()
            ->get();

        return view('kepala.transaksi', compact('peminjaman', 'pengembalian'));
    }
}
