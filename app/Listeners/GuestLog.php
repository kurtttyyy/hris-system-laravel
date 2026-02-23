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
        ModelsGuestLog::create([
            'message' => $event->message,
        ]);
    }
}
