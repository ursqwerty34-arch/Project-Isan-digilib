<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class AnggotaNotifController extends Controller
{
    public function index()
    {
        $user     = Auth::user();
        $tomorrow = now()->addDay()->toDateString();

        Loan::where('user_id', $user->id)->where('status', 'dipinjam')->where('due_date', $tomorrow)
            ->get()->each(function ($loan) use ($user) {
                if (!Notification::where('user_id', $user->id)->where('type', 'reminder')->whereJsonContains('data->loan_id', $loan->id)->exists()) {
                    Notification::create([
                        'user_id' => $user->id,
                        'type'    => 'reminder',
                        'message' => 'Reminder: Batas pengembalian buku kamu besok.',
                        'data'    => ['loan_id' => $loan->id, 'judul' => $loan->book->title, 'due_date' => $loan->due_date],
                    ]);
                }
            });

        Notification::where('user_id', $user->id)->whereNotNull('read_at')->where('read_at', '<=', now()->subHours(24))->delete();

        return view('anggota.notif', ['notifications' => Notification::where('user_id', $user->id)->latest()->get()]);
    }

    public function markRead(Notification $notification)
    {
        $notification->update(['is_read' => true, 'read_at' => now()]);
        return response()->json(['success' => true]);
    }
}
