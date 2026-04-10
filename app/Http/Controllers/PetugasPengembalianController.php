<?php

namespace App\Http\Controllers;

use App\Helpers\NotifHelper;
use App\Models\BookReturn;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasPengembalianController extends Controller
{
    public function index()
    {
        $pending = Loan::with(['book', 'user'])
            ->where('pengajuan_status', 'disetujui')
            ->where('status', 'dipinjam')
            ->where('return_requested', true)
            ->latest()
            ->paginate(10, ['*'], 'pending_page')
            ->withQueryString();

        $confirmed = BookReturn::with(['loan.book', 'loan.user'])
            ->where('confirmed_by', Auth::id())
            ->latest()
            ->paginate(10, ['*'], 'confirmed_page')
            ->withQueryString();

        return view('petugas.pengembalian', compact('pending', 'confirmed'));
    }

    public function konfirmasi(Request $request, Loan $loan)
    {
        $returnDate = $request->input('return_date', now()->toDateString());

        BookReturn::create([
            'loan_id'      => $loan->id,
            'confirmed_by' => Auth::id(),
            'return_date'  => $returnDate,
            'fine'         => 0,
            'fine_status'  => 'tidak_ada',
        ]);

        $loan->update(['status' => 'dikembalikan']);
        $loan->book->increment('stock');

        NotifHelper::send($loan->user_id, 'return_success',
            'Pengembalian buku kamu berhasil, terima kasih!',
            ['judul' => $loan->book->title, 'return_date' => $returnDate]
        );

        return response()->json(['success' => true]);
    }

    public function hitungDenda(Loan $loan)
    {
        $dueDate    = \Carbon\Carbon::parse($loan->due_date);
        $returnDate = now();
        $telat      = $returnDate->gt($dueDate) ? $returnDate->diffInDays($dueDate) : 0;
        $dendaPerHari = 5000;
        $totalDenda   = $telat * $dendaPerHari;

        return response()->json([
            'success'         => true,
            'judul'           => $loan->book->title . ' (' . $loan->book->kode_buku . ')',
            'peminjam'        => $loan->user->name,
            'tgl_pinjam'      => \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y'),
            'tgl_jatuh'       => \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y'),
            'tgl_kembali'     => $returnDate->format('d/m/Y'),
            'tgl_kembali_raw' => $returnDate->toDateString(),
            'telat'           => $telat,
            'denda_per_hari'  => $dendaPerHari,
            'total_denda'     => $totalDenda,
        ]);
    }

    public function kirimDenda(Request $request, Loan $loan)
    {
        $request->validate([
            'telat'       => 'required|integer|min:0',
            'total_denda' => 'required|integer|min:0',
            'return_date' => 'required|date',
        ]);

        BookReturn::create([
            'loan_id'      => $loan->id,
            'confirmed_by' => Auth::id(),
            'return_date'  => $request->return_date,
            'fine'         => $request->total_denda,
            'fine_status'  => 'belum_lunas',
        ]);

        $loan->update(['status' => 'dikembalikan']);
        $loan->book->increment('stock');

        NotifHelper::send($loan->user_id, 'return_fine',
            'Pengembalian buku kamu telat, kamu dikenakan denda.',
            [
                'judul'       => $loan->book->title,
                'due_date'    => $loan->due_date,
                'return_date' => $request->return_date,
                'telat'       => $request->telat,
                'total_denda' => $request->total_denda,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function lunasDenda(BookReturn $bookReturn)
    {
        $bookReturn->update(['fine_status' => 'lunas']);
        return response()->json(['success' => true]);
    }

    public function detail(BookReturn $bookReturn)
    {
        $bookReturn->load(['loan.book', 'loan.user']);
        return view('petugas.pengembalian.detail', compact('bookReturn'));
    }

    public function cetak(BookReturn $bookReturn)
    {
        $bookReturn->load(['loan.book', 'loan.user']);
        return view('petugas.pengembalian.cetak', compact('bookReturn'));
    }
}
