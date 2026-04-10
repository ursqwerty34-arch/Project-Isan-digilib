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
            ->paginate(10, ['*'], 'pinjam_page')
            ->withQueryString();

        $pengembalian = BookReturn::with(['loan.book', 'loan.user'])
            ->latest()
            ->paginate(10, ['*'], 'kembali_page')
            ->withQueryString();

        return view('kepala.transaksi', compact('peminjaman', 'pengembalian'));
    }
}
