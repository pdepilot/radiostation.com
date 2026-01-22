<x-filament-panels::page class="fi-dashboard-page">
    @if (method_exists($this, 'filtersForm'))
        {{ $this->filtersForm }}
    @endif

    {{-- Widgets are automatically rendered by the base page template via getHeaderWidgets() and getFooterWidgets() --}}
    {{-- No need to manually render widgets here to avoid duplication --}}
</x-filament-panels::page>
