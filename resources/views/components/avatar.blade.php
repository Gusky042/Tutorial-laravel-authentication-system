@props(['name', 'size' => 'size-10'])

@php
    $initials = collect(preg_split('/\s+/', trim($name)))
        ->filter()
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->take(2)
        ->implode('');

    // The same name always gets the same colour pair.
    $tints = [
        'bg-blue-100 text-blue-800',
        'bg-sky-100 text-sky-800',
        'bg-teal-100 text-teal-800',
        'bg-amber-100 text-amber-800',
        'bg-orange-100 text-orange-800',
        'bg-indigo-100 text-indigo-800',
    ];
    $tint = $tints[crc32($name) % count($tints)];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex shrink-0 items-center justify-center rounded-full text-sm font-semibold {$size} {$tint}"]) }}
      aria-hidden="true">{{ $initials }}</span>
