<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

// routes/channels.php
Broadcast::channel('chat.{receiverId}', function ($user, $receiverId) {
    Log::info('Channel auth attempt', [
        'user_id' => $user->id,
        'receiver_id' => $receiverId,
    ]);

    // Allow if user is either sender or receiver
    return (int) $user->id === (int) $receiverId;
});
