<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Appointment $appointment,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $appt = $this->appointment;

        return (new MailMessage)
            ->subject('Agendamento Confirmado - ' . $appt->service->name)
            ->greeting('Ola, ' . $notifiable->name . '!')
            ->line('Seu agendamento foi confirmado.')
            ->line('**Servico:** ' . $appt->service->name)
            ->line('**Data:** ' . $appt->date->format('d/m/Y'))
            ->line('**Horario:** ' . $appt->start_time . ' - ' . $appt->end_time)
            ->line('Nos vemos em breve!');
    }
}
