<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Support\Facades\Auth;

class AnggotaTransaksiController extends Controller
{
    public function index()
    {
        $uid = Auth::id();
        return view('anggota.transaksi', [
            'aktif'        => Loan::with(['book', 'bookReturn'])->where('user_id', $uid)->where('status', '!=', 'dikembalikan')->latest()->paginate(10, ['*'], 'aktif_page')->withQueryString(),
            'dikembalikan' => Loan::with(['book', 'bookReturn'])->where('user_id', $uid)->where('status', 'dikembalikan')->latest()->paginate(10, ['*'], 'kembali_page')->withQueryString(),
        ]);
    }

    public function kembalikan(Loan $loan)
    {
        abort_if($loan->user_id !== Auth::id(), 403);
        $loan->update(['return_requested' => true]);
        return response()->json(['success' => true]);
    }
}
