<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Agenda</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Date selector -->
            <div class="mb-6 flex items-center gap-4">
                <a href="{{ route('provider.appointments.index', ['date' => \Carbon\Carbon::parse($date)->subDay()->toDateString()]) }}"
                   class="px-3 py-2 bg-white rounded-lg shadow-sm hover:bg-gray-50 text-sm">&larr;</a>
                <form method="GET" action="{{ route('provider.appointments.index') }}" class="flex items-center gap-2">
                    <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()"
                           class="rounded-md border-gray-300 text-sm">
                </form>
                <a href="{{ route('provider.appointments.index', ['date' => \Carbon\Carbon::parse($date)->addDay()->toDateString()]) }}"
                   class="px-3 py-2 bg-white rounded-lg shadow-sm hover:bg-gray-50 text-sm">&rarr;</a>
                <span class="text-gray-600 text-sm">
                    {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d \d\e F \d\e Y') }}
                </span>
            </div>

            <!-- Appointments list -->
            @if ($appointments->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-8 text-center text-gray-500">
                    Nenhum agendamento para esta data.
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($appointments as $appointment)
                        <div class="bg-white shadow-sm sm:rounded-lg p-4 flex items-center justify-between">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $appointment->start_time }} - {{ $appointment->end_time }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $appointment->client->name }} — {{ $appointment->service->name }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $appointment->formattedPrice() }}
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 text-xs rounded-full bg-{{ $appointment->status->color() }}-100 text-{{ $appointment->status->color() }}-800">
                                    {{ $appointment->status->label() }}
                                </span>
                                @if ($appointment->status === \App\Enums\AppointmentStatus::Pending)
                                    <form method="POST" action="{{ route('provider.appointments.updateStatus', $appointment) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button class="px-3 py-1 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700">Confirmar</button>
                                    </form>
                                @endif
                                @if ($appointment->status === \App\Enums\AppointmentStatus::Confirmed)
                                    <form method="POST" action="{{ route('provider.appointments.updateStatus', $appointment) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button class="px-3 py-1 text-xs bg-green-600 text-white rounded-lg hover:bg-green-700">Concluir</button>
                                    </form>
                                @endif
                                @if (in_array($appointment->status, [\App\Enums\AppointmentStatus::Pending, \App\Enums\AppointmentStatus::Confirmed]))
                                    <form method="POST" action="{{ route('provider.appointments.updateStatus', $appointment) }}" onsubmit="return confirm('Cancelar agendamento?')">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
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
