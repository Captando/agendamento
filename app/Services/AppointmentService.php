<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Events\AppointmentBooked;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

final class AppointmentService
{
    public function __construct(
        private readonly AvailabilityService $availability,
    ) {}

    /**
     * Book an appointment with pessimistic locking to prevent double-booking.
     */
    public function book(
        User $provider,
        User $client,
        Service $service,
        Carbon $date,
        string $startTime,
    ): Appointment {
        return DB::transaction(function () use ($provider, $client, $service, $date, $startTime) {
            $occupyingStatuses = array_map(
                fn (AppointmentStatus $s) => $s->value,
                AppointmentStatus::occupying(),
            );

            // Pessimistic lock on existing appointments for this provider+date
            Appointment::query()
                ->where('provider_id', $provider->id)
                ->where('date', $date->toDateString())
                ->whereIn('status', $occupyingStatuses)
                ->lockForUpdate()
                ->get();

            // Re-verify availability inside the transaction
            $availableSlots = $this->availability->getAvailableSlots($provider, $service, $date);

            $slotExists = $availableSlots->contains(
                fn (array $slot) => $slot['start'] === $startTime
            );

            if (! $slotExists) {
                throw new \DomainException(
                    'O horario selecionado nao esta mais disponivel. Por favor, escolha outro.'
                );
            }

            $startCarbon = Carbon::parse($startTime);
            $endCarbon = $startCarbon->copy()->addMinutes($service->duration_minutes);

            $appointment = Appointment::create([
                'provider_id' => $provider->id,
                'client_id' => $client->id,
                'service_id' => $service->id,
                'date' => $date->toDateString(),
                'start_time' => $startCarbon->format('H:i'),
                'end_time' => $endCarbon->format('H:i'),
                'status' => AppointmentStatus::Pending,
                'price_cents_snapshot' => $service->price_cents,
            ]);

            event(new AppointmentBooked($appointment));

            return $appointment;
        });
    }
}
