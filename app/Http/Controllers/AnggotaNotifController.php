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

        $reminders = Loan::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->where('due_date', $tomorrow)
            ->get();

        foreach ($reminders as $loan) {
            $alreadySent = Notification::where('user_id', $user->id)
                ->where('type', 'reminder')
                ->whereJsonContains('data->loan_id', $loan->id)
                ->exists();

            if (!$alreadySent) {
                Notification::create([
                    'user_id' => $user->id,
                    'type'    => 'reminder',
                    'message' => 'Reminder: Batas pengembalian buku kamu besok.',
                    'data'    => ['loan_id' => $loan->id, 'judul' => $loan->book->title, 'due_date' => $loan->due_date],
                ]);
            }
        }

        $notifications = Notification::where('user_id', $user->id)->latest()->get();

        return view('anggota.notif', compact('notifications'));
    }

    public function markRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }
}
