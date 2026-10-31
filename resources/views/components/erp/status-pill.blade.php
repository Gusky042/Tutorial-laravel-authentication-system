@props(['tone' => 'info'])

@php
    // Tinted background with dark text, so the label stays readable; colour is never the only cue.
    $classes = match ($tone) {
        'good' => 'bg-[#0ca30c]/12 text-[#006300]',
        'warning' => 'bg-[#fab219]/20 text-[#7a5200]',
        'critical' => 'bg-[#d03b3b]/12 text-[#a12828]',
        default => 'bg-[#2a4bc9]/10 text-[#1f3bb3]',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center whitespace-nowrap rounded-md px-2 py-0.5 text-xs font-semibold {$classes}"]) }}>
    {{ $slot }}
</span>
