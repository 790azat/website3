{{--
    Brand logo: the house mark + "globus.fun" wordmark image. The artwork has
    a white "globus" and an orange ".fun", so on light backgrounds it sits on a navy
    chip. Options: $invert (bool) for dark backgrounds, $size ('sm' | 'md').
--}}
@php
    $invert = $invert ?? false;
    $height = ($size ?? 'md') === 'sm' ? 'h-6' : 'h-8';
    $alt = 'globus.fun';
@endphp
@if ($invert)
    <img src="/images/brand/logo.webp?v=2" alt="{{ $alt }}" width="781" height="128" class="{{ $height }} w-auto shrink-0">
@else
    <span class="inline-flex rounded-md bg-navy-950 px-3 py-2">
        <img src="/images/brand/logo.webp?v=2" alt="{{ $alt }}" width="781" height="128" class="{{ $height }} w-auto shrink-0">
    </span>
@endif
