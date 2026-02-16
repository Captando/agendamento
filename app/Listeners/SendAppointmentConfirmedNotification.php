<?php

namespace App\Listeners;

use App\Events\AppointmentConfirmed;
use App\Notifications\AppointmentConfirmedNotification;

class SendAppointmentConfirmedNotification
{
    public function handle(AppointmentConfirmed $event): void
    {
        $event->appointment->client->notify(
            new AppointmentConfirmedNotification($event->appointment)
        );
    }
}
