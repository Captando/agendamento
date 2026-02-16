<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\BlockedDate;
use App\Models\BusinessHour;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

final class AvailabilityService
{
    private const SLOT_GRANULARITY = 15; // minutes

    /**
     * Return all available time slots for a provider on a given date,
     * considering a specific service's duration.
     *
     * @return Collection<int, array{start: string, end: string}>
     */
    public function getAvailableSlots(User $provider, Service $service, Carbon $date): Collection
    {
        // Step 1: Get business hours for this weekday
        $businessHour = BusinessHour::query()
            ->where('provider_id', $provider->id)
            ->where('day_of_week', $date->dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (! $businessHour) {
            return collect();
        }

        // Step 2: Check if entire day is blocked
        $fullDayBlock = BlockedDate::query()
            ->where('provider_id', $provider->id)
            ->where('date', $date->toDateString())
            ->whereNull('start_time')
            ->whereNull('end_time')
            ->exists();

        if ($fullDayBlock) {
            return collect();
        }

        // Step 3: Collect all unavailable intervals
        $unavailableIntervals = $this->getUnavailableIntervals($provider, $date);

        // Step 4: Build working windows (accounting for break)
        $workingWindows = $this->buildWorkingWindows($businessHour);

        // Step 5: Generate candidate slots and filter
        $durationMinutes = $service->duration_minutes;
        $slots = collect();

        foreach ($workingWindows as [$windowStart, $windowEnd]) {
            $candidate = $windowStart->copy();

            while ($candidate->copy()->addMinutes($durationMinutes)->lte($windowEnd)) {
                $slotStart = $candidate->copy();
                $slotEnd = $candidate->copy()->addMinutes($durationMinutes);

                $overlaps = $unavailableIntervals->contains(
                    fn (array $interval) => $this->intervalsOverlap(
                        $slotStart, $slotEnd,
                        $interval['start'], $interval['end'],
                    )
                );

                $isInPast = $date->isToday()
                    && $slotStart->lt(Carbon::now($provider->timezone));

                if (! $overlaps && ! $isInPast) {
                    $slots->push([
                        'start' => $slotStart->format('H:i'),
                        'end' => $slotEnd->format('H:i'),
                    ]);
                }

                $candidate->addMinutes(self::SLOT_GRANULARITY);
            }
        }

        return $slots;
    }

    /**
     * @return Collection<int, array{start: Carbon, end: Carbon}>
     */
    private function getUnavailableIntervals(User $provider, Carbon $date): Collection
    {
        $occupyingStatuses = array_map(
            fn (AppointmentStatus $s) => $s->value,
            AppointmentStatus::occupying(),
        );

        $appointments = Appointment::query()
            ->where('provider_id', $provider->id)
            ->where('date', $date->toDateString())
            ->whereIn('status', $occupyingStatuses)
            ->get(['start_time', 'end_time'])
            ->map(fn (Appointment $a) => [
                'start' => Carbon::parse($a->start_time),
                'end' => Carbon::parse($a->end_time),
            ]);

        $blockedPartials = BlockedDate::query()
            ->where('provider_id', $provider->id)
            ->where('date', $date->toDateString())
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->get(['start_time', 'end_time'])
            ->map(fn (BlockedDate $b) => [
                'start' => Carbon::parse($b->start_time),
                'end' => Carbon::parse($b->end_time),
            ]);

        return $appointments->merge($blockedPartials);
    }

    /**
     * @return array<int, array{0: Carbon, 1: Carbon}>
     */
    private function buildWorkingWindows(BusinessHour $bh): array
    {
        $dayStart = Carbon::parse($bh->start_time);
        $dayEnd = Carbon::parse($bh->end_time);

        if ($bh->break_start && $bh->break_end) {
            $breakStart = Carbon::parse($bh->break_start);
            $breakEnd = Carbon::parse($bh->break_end);

            return [
                [$dayStart, $breakStart],
                [$breakEnd, $dayEnd],
            ];
        }

        return [[$dayStart, $dayEnd]];
    }

    private function intervalsOverlap(
        Carbon $aStart, Carbon $aEnd,
        Carbon $bStart, Carbon $bEnd,
    ): bool {
        return $aStart->lt($bEnd) && $bStart->lt($aEnd);
    }
}
