{{--
    Cover area for an article card: the article image when it exists,
    otherwise generated artwork (a blueprint grid with the section icon).
    Expects $article; optional $iconClass.
--}}
@if ($article['image'])
    <img
        src="{{ asset('images/'.$article['image']) }}"
        alt="{{ $article['title'] }}"
        loading="lazy"
        decoding="async"
        class="absolute inset-0 size-full object-cover transition duration-500 group-hover:scale-105"
    />
@else
    <div class="absolute inset-0 bg-navy-800">
        <div class="absolute inset-0 bg-[linear-gradient(var(--color-navy-700)_1px,transparent_1px),linear-gradient(90deg,var(--color-navy-700)_1px,transparent_1px)] [background-size:22px_22px] opacity-70"></div>
        <div class="absolute -right-10 -bottom-10 size-40 rotate-12 rounded-2xl bg-brand-500/25"></div>
        <div class="absolute top-4 left-4 size-3 rotate-45 bg-zest-400"></div>
    </div>
    <flux:icon name="{{ $article['section_icon'] }}" class="relative {{ $iconClass ?? 'size-12' }} text-white/90 transition duration-500 group-hover:scale-110" />
@endif
