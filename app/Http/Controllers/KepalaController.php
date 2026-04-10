<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookReturn;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class KepalaController extends Controller
{
    public function dashboard()
    {
        // Stats
        $totalAnggota      = User::where('role', 'anggota')->count();
        $totalPetugas      = User::where('role', 'petugas')->count();
        $totalBuku         = Book::count();
        $totalPeminjaman   = Loan::count();
        $totalPengembalian = BookReturn::count();

        // Grafik 1: Peminjaman & Pengembalian per bulan (12 bulan terakhir)
        $bulanLabels = [];
        $dataPinjam  = [];
        $dataKembali = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $bulanLabels[] = $date->translatedFormat('M Y');
            $dataPinjam[]  = Loan::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)->count();
            $dataKembali[] = BookReturn::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)->count();
        }

        // Grafik 2: Status peminjaman (pie)
        $statusPinjam = [
            'Pending'      => Loan::where('pengajuan_status', 'pending')->count(),
            'Dipinjam'     => Loan::where('status', 'dipinjam')->count(),
            'Dikembalikan' => Loan::where('status', 'dikembalikan')->count(),
            'Ditolak'      => Loan::where('pengajuan_status', 'ditolak')->count(),
        ];

        // Grafik 3: Top 5 buku terpopuler
        $topBuku = Loan::select('book_id', DB::raw('count(*) as total'))
            ->with('book:id,title')
            ->groupBy('book_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Grafik 4: Anggota baru per bulan (6 bulan terakhir)
        $anggotaLabels = [];
        $dataAnggota   = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $anggotaLabels[] = $date->translatedFormat('M Y');
            $dataAnggota[]   = User::where('role', 'anggota')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)->count();
        }

        return view('dashboard.kepala', compact(
            'totalAnggota', 'totalPetugas', 'totalBuku', 'totalPeminjaman', 'totalPengembalian',
            'bulanLabels', 'dataPinjam', 'dataKembali',
            'statusPinjam', 'topBuku',
            'anggotaLabels', 'dataAnggota'
        ));
    }
}
