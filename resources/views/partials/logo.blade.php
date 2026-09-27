{{--
    Brand logo: the Contractor-mag.com mark + wordmark image. The artwork has
    a white "Contractor" and ".com", so on light backgrounds it sits on a navy
    chip. Options: $invert (bool) for dark backgrounds, $size ('sm' | 'md').
--}}
@php
    $invert = $invert ?? false;
    $height = ($size ?? 'md') === 'sm' ? 'h-6' : 'h-8';
    $alt = config('app.name', 'Laravel');
@endphp
@if ($invert)
    <img src="/images/brand/logo.webp" alt="{{ $alt }}" width="1200" height="128" class="{{ $height }} w-auto shrink-0">
@else
    <span class="inline-flex rounded-md bg-navy-950 px-3 py-2">
        <img src="/images/brand/logo.webp" alt="{{ $alt }}" width="1200" height="128" class="{{ $height }} w-auto shrink-0">
    </span>
@endif
