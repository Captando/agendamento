<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $user->name }} - Agendamento Online</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-lg mx-auto px-4 py-8">
        <!-- Provider header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-indigo-600 rounded-full mx-auto flex items-center justify-center text-white text-2xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h1 class="mt-4 text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
            <p class="text-gray-500">Agendamento Online</p>
        </div>

        @if ($selectedService && auth()->check() && auth()->user()->isClient())
            <!-- Slot picker for selected service -->
            <div class="mb-6">
                <a href="{{ route('provider.public', $user->slug) }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Voltar aos servicos</a>
            </div>
            @livewire('client.slot-picker', ['provider' => $user, 'service' => $selectedService])
        @else
            <!-- Services list -->
            @if ($services->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    Nenhum servico disponivel no momento.
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($services as $service)
                        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $service->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $service->duration_minutes }} min</p>
                                @if ($service->description)
                                    <p class="text-xs text-gray-400 mt-1">{{ $service->description }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-gray-900">{{ $service->formattedPrice() }}</div>
                                @auth
                                    @if (auth()->user()->isClient())
                                        <a href="{{ route('provider.public', $user->slug) }}?service={{ $service->id }}"
                                           class="mt-2 inline-block px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-colors">
                                            Agendar
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                       class="mt-2 inline-block px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-colors">
                                        Agendar
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
    @livewireScripts
</body>
</html>
