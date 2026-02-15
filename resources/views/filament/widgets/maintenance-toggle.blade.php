<x-filament-widgets::widget>
    <x-filament::section>
        <div class="fi-section-content">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="display: inline-flex; align-items: center; font-size: 14px; font-weight: 600;">
                    @if ($enabled)
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px; margin-right: 8px; color: #16a34a;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        ON (Aktif)
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px; margin-right: 8px; color: #dc2626;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        OFF (Nonaktif)
                    @endif
                </span>
                <x-filament::button 
                    wire:click="toggleMaintenance"
                    color="{{ $enabled ? 'danger' : 'success' }}">
                    {{ $enabled ? 'Nonaktifkan Maintenance' : 'Aktifkan Maintenance' }}
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
