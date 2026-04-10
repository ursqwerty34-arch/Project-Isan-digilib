<?php

namespace App\Http\Controllers;

use App\Models\BookReturn;
use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PetugasDashboardController extends Controller
{
    public function index()
    {
        $id = Auth::id();

        // Stats
        $totalPending      = Loan::where('pengajuan_status', 'pending')->count();
        $totalDipinjam     = Loan::where('status', 'dipinjam')->count();
        $totalDikembalikan = BookReturn::count();
        $totalBuku         = Book::count();
        $totalAnggota      = User::where('role', 'anggota')->count();

        // Grafik 1: Pengajuan masuk per bulan (12 bulan)
        $bulanLabels  = [];
        $dataPending  = [];
        $dataDisetujui = [];
        $dataDitolak  = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $bulanLabels[]   = $date->translatedFormat('M Y');
            $dataPending[]   = Loan::where('pengajuan_status', 'pending')
                ->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count();
            $dataDisetujui[] = Loan::where('pengajuan_status', 'disetujui')
                ->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count();
            $dataDitolak[]   = Loan::where('pengajuan_status', 'ditolak')
                ->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count();
        }

        // Grafik 2: Pengembalian per bulan (12 bulan)
        $dataKembali = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $dataKembali[] = BookReturn::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)->count();
        }

        // Grafik 3: Top 5 buku terpopuler
        $topBuku = Loan::select('book_id', DB::raw('count(*) as total'))
            ->with('book:id,title')
            ->groupBy('book_id')
            ->orderByDesc('total')
            ->limit(5)->get();

        // Grafik 4: Status denda
        $dendaStats = [
            'Tidak Ada' => BookReturn::where('fine_status', 'tidak_ada')->count(),
            'Belum Lunas' => BookReturn::where('fine_status', 'belum_lunas')->count(),
            'Lunas'      => BookReturn::where('fine_status', 'lunas')->count(),
        ];

        return view('dashboard.petugas', compact(
            'totalPending', 'totalDipinjam', 'totalDikembalikan', 'totalBuku', 'totalAnggota',
            'bulanLabels', 'dataPending', 'dataDisetujui', 'dataDitolak', 'dataKembali',
            'topBuku', 'dendaStats'
        ));
    }
}
