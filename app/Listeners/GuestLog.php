<?php

namespace App\Listeners;

use App\Events\GuestLog as EventsGuestLog;
use App\Models\GuestLog as ModelsGuestLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GuestLog
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EventsGuestLog $event): void
    {
        $message = trim((string) $event->message);

        // Remove route/path fragments from log messages before saving them for admin-facing summaries.
        $message = preg_replace('/\s*(?:https?:\/\/\S+|\/[A-Za-z0-9_\-\/?=&.%]+)$/', '', $message) ?? $message;
        $message = preg_replace('/\s*\|\s*route:.*$/i', '', $message) ?? $message;
        $message = preg_replace('/\s*\|\s*path:.*$/i', '', $message) ?? $message;

        ModelsGuestLog::create([
            'message' => $message !== '' ? $message : 'Viewed',
        ]);
    }
}
