<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Meus Agendamentos</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if ($appointments->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-12 text-center">
                    <p class="text-gray-500">Voce ainda nao tem agendamentos.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($appointments as $appointment)
                        <div class="bg-white shadow-sm sm:rounded-lg p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $appointment->date->format('d/m/Y') }} — {{ $appointment->start_time }} - {{ $appointment->end_time }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $appointment->service->name }} com {{ $appointment->provider->name }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $appointment->formattedPrice() }}
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 text-xs rounded-full bg-{{ $appointment->status->color() }}-100 text-{{ $appointment->status->color() }}-800">
                                    {{ $appointment->status->label() }}
                                </span>
                                @if (in_array($appointment->status, \App\Enums\AppointmentStatus::occupying()))
                                    <form method="POST" action="{{ route('client.appointments.cancel', $appointment) }}" onsubmit="return confirm('Cancelar agendamento?')">
                                        @csrf @method('PATCH')
                                        <button class="px-3 py-1 text-xs bg-red-600 text-white rounded-lg hover:bg-red-700">Cancelar</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
