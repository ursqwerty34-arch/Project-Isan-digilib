<?php

namespace App\Http\Controllers;

use App\Models\BookReturn;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    private function buildQuery(string $jenis, string $dari, string $sampai, ?string $bulan)
    {
        if ($jenis === 'peminjaman') {
            return Loan::with(['book', 'user'])
                ->whereIn('pengajuan_status', ['disetujui', 'ditolak'])
                ->whereBetween('loan_date', [$dari, $sampai])
                ->when($bulan, fn($q) => $q->whereMonth('loan_date', $bulan))
                ->latest('loan_date');
        }
        return BookReturn::with(['loan.book', 'loan.user'])
            ->whereBetween('return_date', [$dari, $sampai])
            ->when($bulan, fn($q) => $q->whereMonth('return_date', $bulan))
            ->latest('return_date');
    }

    public function index(Request $request)
    {
        $jenis = $request->jenis ?? 'peminjaman';
        $data  = collect();
        $generated = false;

        if ($request->dari && $request->sampai) {
            $generated = true;
            $data = $this->buildQuery($jenis, $request->dari, $request->sampai, $request->bulan)->get();
        }

        $view = Auth::user()->role === 'petugas' ? 'petugas.laporan' : 'kepala.laporan';
        return view($view, array_merge($request->only(['dari', 'sampai', 'bulan']), compact('jenis', 'data', 'generated')));
    }

    public function cetak(Request $request)
    {
        $jenis = $request->jenis ?? 'peminjaman';
        $data  = $this->buildQuery($jenis, $request->dari, $request->sampai, $request->bulan)->get();
        return view('laporan.cetak', array_merge($request->only(['dari', 'sampai', 'bulan']), compact('jenis', 'data')));
    }
}
