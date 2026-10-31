{{--
    Corevia logo: the "module cube" mark plus the wordmark.
    The three faces stand for finance, inventory and people: separate modules forming one system.

    Props:
      variant  'light' for light backgrounds, 'dark' for navy ones (the left face turns white)
      size     'sm' | 'md' | 'lg'
      tagline  show "Enterprise suite" under the name
      wordmark set to false to show only the cube
      gap      colour of the thin lines between faces; match it to the background
--}}
@props(['variant' => 'light', 'size' => 'md', 'tagline' => false, 'wordmark' => true, 'gap' => null])

@php
    $dark = $variant === 'dark';
    $faces = [
        'top' => '#2a47d6',
        'left' => $dark ? '#ffffff' : '#131f3b',
        'right' => '#f5a623',
    ];
    $gap ??= $dark ? '#131f3b' : '#ffffff';

    [$markSize, $textSize, $taglineSize] = match ($size) {
        'sm' => ['size-7', 'text-xl', 'text-[9px]'],
        'lg' => ['size-14', 'text-4xl', 'text-xs'],
        default => ['size-9', 'text-2xl', 'text-[10px]'],
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-3']) }}>
    <svg viewBox="0 0 100 100" class="{{ $markSize }} shrink-0" @if ($wordmark) aria-hidden="true" @else role="img" aria-label="{{ config('app.name') }}" @endif>
        <g stroke="{{ $gap }}" stroke-width="4" stroke-linejoin="round">
            <path d="M50 6 90 29 50 52 10 29Z" fill="{{ $faces['top'] }}" />
            <path d="M10 29 50 52v44L10 73Z" fill="{{ $faces['left'] }}" />
            <path d="M90 29 50 52v44l40-23Z" fill="{{ $faces['right'] }}" />
        </g>
    </svg>

    @if ($wordmark)
        <span class="leading-none">
            <span class="block font-brand {{ $textSize }} font-extrabold tracking-tight {{ $dark ? 'text-white' : 'text-[#131f3b]' }}">{{ config('app.name') }}</span>
            @if ($tagline)
                <span class="mt-1 block {{ $taglineSize }} font-semibold uppercase tracking-[0.25em] {{ $dark ? 'text-slate-300' : 'text-slate-500' }}">Enterprise suite</span>
            @endif
        </span>
    @endif
</span>
