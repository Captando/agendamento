<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Painel do Prestador
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Servicos -->
                <a href="{{ route('provider.services.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-semibold text-gray-900">Servicos</h3>
                    <p class="mt-2 text-sm text-gray-600">Gerencie seus servicos, precos e duracao.</p>
                </a>

                <!-- Horarios -->
                <a href="{{ route('provider.business-hours.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-semibold text-gray-900">Horarios de Trabalho</h3>
                    <p class="mt-2 text-sm text-gray-600">Configure seus dias e horarios de atendimento.</p>
                </a>

                <!-- Agenda -->
                <a href="{{ route('provider.appointments.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-semibold text-gray-900">Agenda</h3>
                    <p class="mt-2 text-sm text-gray-600">Visualize e gerencie seus agendamentos.</p>
                </a>
            </div>

            <!-- Public link -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900">Sua pagina publica</h3>
                <p class="mt-2 text-sm text-gray-600">Compartilhe este link com seus clientes:</p>
                <div class="mt-3 flex items-center gap-3">
                    <code class="bg-gray-100 px-4 py-2 rounded-lg text-sm flex-1 truncate">
                        {{ url('/p/' . auth()->user()->slug) }}
                    </code>
                    <button onclick="navigator.clipboard.writeText('{{ url('/p/' . auth()->user()->slug) }}')"
                            class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-colors">
                        Copiar
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
