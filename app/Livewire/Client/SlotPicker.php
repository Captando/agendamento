<?php

namespace App\Livewire\Client;

use App\Models\Service;
use App\Models\User;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Livewire\Component;

class SlotPicker extends Component
{
    public int $providerId;
    public int $serviceId;
    public string $providerSlug;
    public string $serviceName;
    public int $durationMinutes;
    public string $formattedPrice;
    public string $selectedDate = '';
    public string $selectedSlot = '';
    public array $availableSlots = [];

    public function mount(User $provider, Service $service): void
    {
        $this->providerId = $provider->id;
        $this->serviceId = $service->id;
        $this->providerSlug = $provider->slug;
        $this->serviceName = $service->name;
        $this->durationMinutes = $service->duration_minutes;
        $this->formattedPrice = $service->formattedPrice();
        $this->selectedDate = Carbon::today()->toDateString();
        $this->loadSlots();
    }

    public function updatedSelectedDate(): void
    {
        $this->selectedSlot = '';
        $this->loadSlots();
    }

    public function loadSlots(): void
    {
        $provider = User::find($this->providerId);
        $service = Service::find($this->serviceId);

        if (! $provider || ! $service) {
            $this->availableSlots = [];
            return;
        }

        $availability = app(AvailabilityService::class);

        $this->availableSlots = $availability
            ->getAvailableSlots(
                $provider,
                $service,
                Carbon::parse($this->selectedDate),
            )
            ->toArray();
    }

    public function selectSlot(string $slot): void
    {
        $this->selectedSlot = $slot;
    }

    public function render()
    {
        return view('livewire.client.slot-picker');
    }
}
