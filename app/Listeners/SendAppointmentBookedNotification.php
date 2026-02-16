<?php

namespace App\Listeners;

use App\Events\AppointmentBooked;
use App\Notifications\AppointmentBookedNotification;

class SendAppointmentBookedNotification
{
    public function handle(AppointmentBooked $event): void
    {
        $appointment = $event->appointment;

        $appointment->provider->notify(
            new AppointmentBookedNotification($appointment)
        );

        $appointment->client->notify(
            new AppointmentBookedNotification($appointment)
        );
    }
}
