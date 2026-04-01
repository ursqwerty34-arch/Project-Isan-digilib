<?php

namespace App\Helpers;

use App\Models\Notification;

class NotifHelper
{
    public static function send(int $userId, string $type, string $message, array $data = []): void
    {
        Notification::create([
            'user_id' => $userId,
            'type'    => $type,
            'message' => $message,
            'data'    => $data,
        ]);
    }
}
