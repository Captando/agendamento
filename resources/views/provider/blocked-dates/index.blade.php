<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Datas Bloqueadas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Add new block -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Bloquear nova data</h3>
                <form method="POST" action="{{ route('provider.blocked-dates.store') }}" class="flex flex-col sm:flex-row gap-3 items-end">
                    @csrf
                    <div>
                        <x-input-label for="date" value="Data" />
                        <input type="date" name="date" id="date" min="{{ now()->toDateString() }}" required
                               class="mt-1 rounded-md border-gray-300 text-sm">
                        <x-input-error :messages="$errors->get('date')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="start_time" value="Inicio (opcional)" />
                        <input type="time" name="start_time" id="start_time"
                               class="mt-1 rounded-md border-gray-300 text-sm">
                    </div>
                    <div>
                        <x-input-label for="end_time" value="Fim (opcional)" />
                        <input type="time" name="end_time" id="end_time"
                               class="mt-1 rounded-md border-gray-300 text-sm">
                    </div>
                    <div>
                        <x-input-label for="reason" value="Motivo (opcional)" />
                        <x-text-input id="reason" name="reason" type="text" class="mt-1" placeholder="Ex: Ferias" />
                    </div>
                    <x-primary-button>Bloquear</x-primary-button>
                </form>
                <p class="mt-2 text-xs text-gray-500">Deixe inicio e fim vazios para bloquear o dia inteiro.</p>
            </div>

            <!-- List existing blocks -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                @if ($blockedDates->isEmpty())
                    <div class="p-8 text-center text-gray-500">
                        Nenhuma data bloqueada.
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periodo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motivo</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acao</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($blockedDates as $blocked)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $blocked->date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $blocked->isFullDay() ? 'Dia inteiro' : $blocked->start_time . ' - ' . $blocked->end_time }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $blocked->reason ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('provider.blocked-dates.destroy', $blocked) }}" method="POST" class="inline" onsubmit="return confirm('Remover bloqueio?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:text-red-900 text-sm">Remover</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
