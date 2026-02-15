<x-filament::widget>
    <x-filament::card class="bg-gray-900 text-white">
        <div class="flex items-center justify-between">
            {{-- Logo di kiri --}}
            <div class="flex items-center space-x-4">
                {{-- <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-10 w-auto" /> --}}
                {{ env('APP_NAME', 'LARAVEL-STARTER') }}
            </div>

            {{-- Versi di kanan --}}
            <div class="flex items-center space-x-6">
                <div class="flex items-center space-x-1 text-sm text-gray-300">
                   
                    <span>Versi: &nbsp;</span>
                </div>

                <div class="flex items-center space-x-1 text-sm text-gray-300">
              
                    <span>2.0.0 (Beta)</span>
                </div>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>
