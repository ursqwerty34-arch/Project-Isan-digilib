<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncBookStock extends Command
{
    protected $signature   = 'book:sync-stock';
    protected $description = 'Sinkronisasi stok buku berdasarkan peminjaman aktif';

    public function handle()
    {
        // Hitung jumlah pinjaman aktif per buku
        $activeLoans = Loan::where('pengajuan_status', 'disetujui')
            ->where('status', 'dipinjam')
            ->select('book_id', DB::raw('COUNT(*) as total'))
            ->groupBy('book_id')
            ->get()
            ->keyBy('book_id');

        $books = Book::all();

        foreach ($books as $book) {
            $dipinjam = $activeLoans->get($book->id)?->total ?? 0;

            // Hitung stok seharusnya berdasarkan jumlah pengembalian
            // stok_asli = stok_sekarang + jumlah_dipinjam_aktif
            // Kita set stok = stok_asli - dipinjam_aktif
            // Karena stok saat ini belum dikurangi, kita kurangi langsung
            if ($dipinjam > 0 && $book->stock >= $dipinjam) {
                $stokBaru = $book->stock - $dipinjam;
                DB::table('books')->where('id', $book->id)->update(['stock' => $stokBaru]);
                $this->info("Fix: {$book->title} | Stok: {$book->stock} → {$stokBaru} (dipinjam: {$dipinjam})");
            } else {
                $this->line("OK : {$book->title} | Stok: {$book->stock} | Dipinjam aktif: {$dipinjam}");
            }
        }

        $this->info('Sync selesai.');
        return 0;
    }
}
