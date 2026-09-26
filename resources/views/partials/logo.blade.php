{{--
    Brand logo: house-and-ledger mark + "HomeLedger" wordmark, drawn inline.
    Options: $invert (bool) for dark backgrounds, $size ('sm' | 'md').
--}}
@php
    $invert = $invert ?? false;
    $small = ($size ?? 'md') === 'sm';
@endphp
<span class="flex items-center gap-2.5">
    @include('partials.logo-mark', ['class' => $small ? 'size-8' : 'size-10'])
    <span @class([
        'font-display leading-none font-extrabold tracking-tight',
        'text-xl' => $small,
        'text-2xl' => ! $small,
        'text-white' => $invert,
        'text-ink' => ! $invert,
    ])>Home<span @class(['text-brand-400' => $invert, 'text-brand-600 dark:text-brand-400' => ! $invert])>Ledger</span></span>
</span>
