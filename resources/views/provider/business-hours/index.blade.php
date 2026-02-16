<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Horarios de Trabalho</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('provider.business-hours.store') }}">
                    @csrf

                    <div class="space-y-4">
                        @for ($day = 0; $day <= 6; $day++)
                            @php $bh = $businessHours->get($day); @endphp
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 p-4 rounded-lg {{ $day % 2 === 0 ? 'bg-gray-50' : '' }}">
                                <div class="w-36">
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                               name="hours[{{ $day }}][is_active]"
                                               value="1"
                                               class="rounded border-gray-300 text-indigo-600"
                                               {{ $bh?->is_active ? 'checked' : '' }}>
                                        <span class="ms-2 text-sm font-medium text-gray-700">
                                            {{ \App\Models\BusinessHour::dayName($day) }}
                                        </span>
                                    </label>
                                </div>

                                <div class="flex flex-wrap items-center gap-2 flex-1">
                                    <input type="time" name="hours[{{ $day }}][start_time]"
                                           value="{{ $bh?->start_time ? \Carbon\Carbon::parse($bh->start_time)->format('H:i') : '09:00' }}"
                                           class="rounded-md border-gray-300 text-sm">
                                    <span class="text-gray-500 text-sm">ate</span>
                                    <input type="time" name="hours[{{ $day }}][end_time]"
                                           value="{{ $bh?->end_time ? \Carbon\Carbon::parse($bh->end_time)->format('H:i') : '18:00' }}"
                                           class="rounded-md border-gray-300 text-sm">

                                    <span class="text-gray-400 text-sm ml-2">Intervalo:</span>
                                    <input type="time" name="hours[{{ $day }}][break_start]"
                                           value="{{ $bh?->break_start ? \Carbon\Carbon::parse($bh->break_start)->format('H:i') : '' }}"
                                           class="rounded-md border-gray-300 text-sm" placeholder="Inicio">
                                    <span class="text-gray-500 text-sm">-</span>
                                    <input type="time" name="hours[{{ $day }}][break_end]"
                                           value="{{ $bh?->break_end ? \Carbon\Carbon::parse($bh->break_end)->format('H:i') : '' }}"
                                           class="rounded-md border-gray-300 text-sm" placeholder="Fim">
                                </div>
                            </div>
                        @endfor
                    </div>

                    <div class="flex justify-end mt-6">
                        <x-primary-button>Salvar Horarios</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
