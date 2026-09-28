{{--
    Main guide page: a standalone in-depth guide with its own layout (distinct
    from the standard article). Settings live in resources/data/articles.php
    under 'programs'; the text in resources/data/programs/{slug}.md.
--}}
@extends('layouts.site')
@use('App\Support\SiteContent')

@php
    $program = SiteContent::program($slug);

    if (! $program) {
        abort(404);
    }

    $siteName = config('app.name', 'Laravel');
    $sectionMeta = SiteContent::section($program['section']);

    $relatedArticle = ($program['related_slug'] ?? null)
        ? SiteContent::article($program['related_slug'])
        : null;

    $title = $program['title'];
    $description = Str::limit($program['intro'], 155);
    $shareArticle = ['image' => $program['hero_image']];

    $ctaButton = view('partials.program-cta', ['program' => $program])->render();
    $rendered = \App\Support\ArticleMarkdown::render($program['body']);
    $bodyHtml = str_replace('<p>[[CTA]]</p>', $ctaButton, $rendered['html']);
@endphp

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-navy-900">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_85%_0%,var(--color-brand-600),transparent_50%)]"></div>
        <div class="absolute -right-24 -bottom-24 size-96 rounded-full border-[48px] border-zest-400/15"></div>

        <div class="relative mx-auto max-w-5xl px-6 pt-10 pb-16 lg:px-8 lg:pb-24">
            <nav class="flex flex-wrap items-center gap-2 text-sm text-navy-300" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" wire:navigate class="font-medium hover:text-white">{{ __('Home') }}</a>
                <span class="text-brand-500">/</span>
                <a href="{{ route('section', $program['section']) }}" wire:navigate class="font-medium hover:text-white">{{ $sectionMeta['title'] ?? '' }}</a>
            </nav>

            <span class="mt-10 inline-flex items-center gap-2 rounded-full bg-zest-400 px-3 py-1 text-xs font-bold tracking-wide text-navy-950 uppercase">
                <flux:icon name="{{ $program['hero_icon'] ?? 'academic-cap' }}" variant="micro" class="size-3.5" />
                {{ __('Main guide') }}
            </span>
            <h1 class="mt-5 font-display text-4xl leading-[1.08] font-bold tracking-tight text-balance text-white sm:text-5xl lg:text-6xl">
                {{ $program['title'] }}
            </h1>
            <p class="mt-6 max-w-3xl text-lg leading-relaxed text-navy-300">{{ $program['intro'] }}</p>

            <a href="{{ $program['cta_url'] }}" target="_blank" rel="noopener noreferrer nofollow" class="btn-zest mt-9 px-8 py-4 text-base">
                {{ $program['cta_label'] }}
                <flux:icon name="arrow-top-right-on-square" variant="mini" class="size-4" />
            </a>
        </div>
    </section>

    <article class="mx-auto max-w-3xl px-6 py-16 lg:px-8">
        {{-- Cover image --}}
        @if ($program['hero_image'])
            <div class="mb-12 overflow-hidden rounded-2xl">
                <img src="{{ asset('images/'.$program['hero_image']) }}" alt="{{ $program['title'] }}" fetchpriority="high" class="aspect-video w-full object-cover" />
            </div>
        @endif

        @include('partials.article-body', ['html' => $bodyHtml])

        {{-- Editorial team card --}}
        <div class="mt-16 rounded-xl border border-line bg-surface p-7">
            <div class="flex items-center gap-3">
                @include('partials.logo', ['size' => 'sm'])
                <span class="text-sm font-semibold text-muted">{{ __('Editorial Team') }}</span>
            </div>
            <p class="mt-5 text-sm leading-relaxed text-body">
                {{ __('We aim to make complicated projects easier to understand by sharing practical guidance, useful questions to ask contractors, and information homeowners can actually use.') }}
            </p>
            <a href="{{ route('team') }}" wire:navigate class="btn-ghost mt-6">{{ __('Learn more about our editors') }}</a>
        </div>
    </article>

    {{-- Related pillar article --}}
    @if ($relatedArticle)
        <section class="border-t border-line bg-surface">
            <div class="mx-auto max-w-5xl px-6 py-16 lg:px-8">
                <span class="eyebrow">{{ __('See also') }}</span>
                <div class="mt-6">
                    @include('partials.article-card', ['article' => $relatedArticle, 'variant' => 'featured'])
                </div>
            </div>
        </section>
    @endif
@endsection
