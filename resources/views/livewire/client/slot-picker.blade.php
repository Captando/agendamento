<div class="w-full max-w-md mx-auto px-4">
    <!-- Service info -->
    <div class="mb-6 p-4 bg-indigo-50 rounded-xl">
        <h3 class="font-semibold text-gray-900">{{ $serviceName }}</h3>
        <p class="text-sm text-gray-600">{{ $durationMinutes }} min — {{ $formattedPrice }}</p>
    </div>

    <!-- Date picker -->
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Escolha a data
    </label>
    <input
        type="date"
        wire:model.live="selectedDate"
        min="{{ now()->toDateString() }}"
        class="w-full rounded-lg border-gray-300 shadow-sm
               focus:border-indigo-500 focus:ring-indigo-500
               text-base py-3"
    />

    <!-- Available slots grid -->
    <div class="mt-6">
        <div wire:loading class="text-center py-4">
            <div class="inline-block w-6 h-6 border-2 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-sm text-gray-500 mt-2">Carregando horarios...</p>
        </div>

        <div wire:loading.remove>
            @if (count($availableSlots) === 0)
                <p class="text-center text-gray-500 py-8">
                    Nenhum horario disponivel nesta data.
                </p>
            @else
                <p class="text-sm text-gray-600 mb-3">
                    Horarios disponiveis ({{ count($availableSlots) }}):
                </p>
                <div class="grid grid-cols-3 gap-2">
                    @foreach ($availableSlots as $slot)
                        <button
                            wire:click="selectSlot('{{ $slot['start'] }}')"
                            @class([
                                'py-3 rounded-lg text-sm font-medium transition-colors',
                                'bg-indigo-600 text-white' => $selectedSlot === $slot['start'],
                                'bg-gray-100 text-gray-800 hover:bg-indigo-50' => $selectedSlot !== $slot['start'],
                            ])
                        >
                            {{ $slot['start'] }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Confirm button -->
    @if ($selectedSlot)
        <div class="mt-6">
            @php
                $selectedEnd = collect($availableSlots)->firstWhere('start', $selectedSlot)['end'] ?? '';
            @endphp
            <p class="text-sm text-gray-600 mb-2">
                {{ $serviceName }} — {{ $selectedSlot }} ate {{ $selectedEnd }}
            </p>
            <form action="{{ route('client.booking.store', [$providerSlug, $serviceId]) }}"
                  method="POST">
                @csrf
                <input type="hidden" name="date" value="{{ $selectedDate }}">
                <input type="hidden" name="start_time" value="{{ $selectedSlot }}">
                <button type="submit"
                        class="w-full bg-indigo-600 text-white py-4 rounded-xl
                               text-lg font-semibold hover:bg-indigo-700
                               active:bg-indigo-800 transition-colors">
                    Confirmar Agendamento
                </button>
            </form>
        </div>
    @endif
</div>
