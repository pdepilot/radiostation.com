@php
    $brandName = filament()->getBrandName();
    $brandLogo = filament()->getBrandLogo();
    $brandLogoHeight = filament()->getBrandLogoHeight() ?? '1.5rem';
    $darkModeBrandLogo = filament()->getDarkModeBrandLogo();
    $hasDarkModeBrandLogo = filled($darkModeBrandLogo);

    $getLogoClasses = fn (bool $isDarkMode): string => \Illuminate\Support\Arr::toCssClasses([
        'fi-logo',
        'flex' => ! $hasDarkModeBrandLogo,
        'flex dark:hidden' => $hasDarkModeBrandLogo && (! $isDarkMode),
        'hidden dark:flex' => $hasDarkModeBrandLogo && $isDarkMode,
    ]);

    $logoStyles = "height: {$brandLogoHeight}";
@endphp

@capture($content, $logo, $isDarkMode = false)
    @if ($logo instanceof \Illuminate\Contracts\Support\Htmlable)
        <a href="{{ route('home') }}" style="text-decoration: none; display: block; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            <div
                {{
                    $attributes
                        ->class([$getLogoClasses($isDarkMode)])
                        ->style([$logoStyles])
                }}
            >
                {{ $logo }}
            </div>
        </a>
    @elseif (filled($logo))
        <a href="{{ route('home') }}" style="text-decoration: none; display: block; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            <img
                alt="{{ __('filament-panels::layout.logo.alt', ['name' => $brandName]) }}"
                src="{{ $logo }}"
                {{
                    $attributes
                        ->class([$getLogoClasses($isDarkMode)])
                        ->style([$logoStyles . '; height: 60px !important; filter: drop-shadow(0 0 10px rgba(255, 0, 0, 0.5));'])
                }}
            />
        </a>
    @else
        <a href="{{ route('home') }}" style="text-decoration: none; display: block;">
            <div
                {{
                    $attributes->class([
                        $getLogoClasses($isDarkMode),
                        'text-xl font-bold leading-5 tracking-tight text-gray-950 dark:text-white',
                    ])
                }}
            >
                {{ $brandName }}
            </div>
        </a>
    @endif
@endcapture

{{ $content($brandLogo) }}

@if ($hasDarkModeBrandLogo)
    {{ $content($darkModeBrandLogo, isDarkMode: true) }}
@endif
