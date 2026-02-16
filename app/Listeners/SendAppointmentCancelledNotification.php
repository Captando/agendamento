<?php

namespace App\Listeners;

use App\Events\AppointmentCancelled;
use App\Notifications\AppointmentCancelledNotification;

class SendAppointmentCancelledNotification
{
    public function handle(AppointmentCancelled $event): void
    {
        $appointment = $event->appointment;

        $appointment->provider->notify(
            new AppointmentCancelledNotification($appointment)
        );

        $appointment->client->notify(
            new AppointmentCancelledNotification($appointment)
        );
    }
}
