<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nome" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role Selection -->
        <div class="mt-4">
            <x-input-label value="Tipo de conta" />
            <div class="mt-2 grid grid-cols-2 gap-3">
                <label class="relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500">
                    <input type="radio" name="role" value="client" class="sr-only" {{ old('role', 'client') === 'client' ? 'checked' : '' }}>
                    <span class="flex flex-1 flex-col">
                        <span class="block text-sm font-medium text-gray-900">Cliente</span>
                        <span class="mt-1 text-xs text-gray-500">Agendar servicos</span>
                    </span>
                    <svg class="h-5 w-5 text-indigo-600 hidden" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                </label>
                <label class="relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500">
                    <input type="radio" name="role" value="provider" class="sr-only" {{ old('role') === 'provider' ? 'checked' : '' }}>
                    <span class="flex flex-1 flex-col">
                        <span class="block text-sm font-medium text-gray-900">Prestador</span>
                        <span class="mt-1 text-xs text-gray-500">Oferecer servicos</span>
                    </span>
                    <svg class="h-5 w-5 text-indigo-600 hidden" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Senha" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmar Senha" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                Ja possui conta?
            </a>

            <x-primary-button class="ms-4">
                Registrar
            </x-primary-button>
        </div>
    </form>

    <script>
        document.querySelectorAll('input[name="role"]').forEach(radio => {
            radio.addEventListener('change', () => {
                document.querySelectorAll('input[name="role"]').forEach(r => {
                    const label = r.closest('label');
                    const check = label.querySelector('svg');
                    if (r.checked) {
                        label.classList.add('border-indigo-500', 'ring-2', 'ring-indigo-500');
                        label.classList.remove('border-gray-300');
                        check.classList.remove('hidden');
                    } else {
                        label.classList.remove('border-indigo-500', 'ring-2', 'ring-indigo-500');
                        label.classList.add('border-gray-300');
                        check.classList.add('hidden');
                    }
                });
            });
            // Trigger initial state
            if (radio.checked) radio.dispatchEvent(new Event('change'));
        });
    </script>
</x-guest-layout>
