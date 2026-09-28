@extends('layouts.site')
@use('App\Support\SiteContent')

@php
    $siteName = config('app.name', 'Laravel');
    $title = null;
    $description = __(':site publishes clear, research-driven guides to home financing, property management, home repairs, and hiring contractors.', ['site' => $siteName]);

    $categories = SiteContent::categories();
    $allArticles = SiteContent::articles();
    $featuredArticle = $allArticles->first();
    $sideArticles = $allArticles->slice(1, 3)->values();
    $latestArticles = $allArticles->slice(4, 6)->values();
    $authors = SiteContent::authors();

    // "Trending" rotator: one article with a cover image per slide, mixing
    // topics round-robin.
    $heroByTopic = $categories
        ->map(fn ($category) => SiteContent::articles($category['id'])->filter(fn ($article) => $article['image'])->values())
        ->filter(fn ($articles) => $articles->isNotEmpty())
        ->values();
    $heroPool = collect(range(0, max(0, ($heroByTopic->max(fn ($articles) => $articles->count()) ?? 0) - 1)))
        ->flatMap(fn (int $i) => $heroByTopic->map(fn ($articles) => $articles[$i] ?? null)->filter());
    if ($heroPool->isEmpty()) {
        $heroPool = $allArticles->slice(10);
    }
    $heroSlides = $heroPool->take(8)->values();

    // Three newest articles per topic for the topic columns.
    $topicColumns = $categories->map(fn ($category) => $category + ['articles' => SiteContent::articles($category['id'])->take(3)]);
@endphp

@section('content')
    {{-- Hero: headline + search on navy, with the topic tiles overlapping below --}}
    <section class="relative z-10 bg-navy-900 text-white">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute inset-0 bg-[linear-gradient(var(--color-navy-800)_1px,transparent_1px),linear-gradient(90deg,var(--color-navy-800)_1px,transparent_1px)] [background-size:36px_36px] [mask-image:radial-gradient(ellipse_at_70%_20%,black,transparent_70%)]"></div>
            <svg class="absolute right-0 -bottom-2 hidden h-72 text-navy-800 lg:block" viewBox="0 0 520 260" fill="currentColor" aria-hidden="true">
                <path d="M0 260V150l90-70 90 70v110zM200 260V110L320 20l120 90v150zM455 260v-80l35-28 30 24v84z" />
                <rect x="250" y="150" width="30" height="40" fill="var(--color-navy-900)" />
                <rect x="360" y="150" width="30" height="40" fill="var(--color-navy-900)" />
                <rect x="75" y="180" width="30" height="80" fill="var(--color-navy-900)" />
            </svg>
        </div>

        <div class="relative mx-auto grid max-w-7xl gap-12 px-6 pt-16 pb-40 lg:grid-cols-12 lg:px-8 lg:pt-24">
            <div class="lg:col-span-7">
                <span class="inline-flex items-center gap-2.5 text-[11px] font-extrabold tracking-[0.22em] text-zest-400 uppercase before:size-2.5 before:rotate-45 before:bg-brand-500">
                    {{ __('Home & property, explained') }}
                </span>
                <h1 class="mt-6 font-display text-5xl leading-[1.02] font-extrabold tracking-tight text-balance sm:text-6xl">
                    {{ __('A closer look at homes, projects, and') }}
                    <span class="text-zest-400">
                        {{ __('the people behind them.') }}
                    </span>
                </h1>
                <p class="mt-8 max-w-xl text-lg leading-relaxed text-navy-300">
                    {{ __('Contractor-Mag covers the practical side of homeownership and property—from mortgages and rentals to repairs, renovations, materials, and working with contractors.') }}
                </p>
                <p class="mt-4 max-w-xl text-lg leading-relaxed text-navy-300">
                    {{ __('Our articles explore the costs, processes, terminology, and considerations that come up throughout a home project, with information presented in a straightforward format.') }}
                </p>

                {{-- Article search: filters an inline index of this locale's articles as you type.
                     Results are real links rendered here (not built in JS) so the static export
                     rewrites them to the right path and language like every other link. --}}
                @php
                    $searchIndex = $allArticles->map(fn ($article) => mb_strtolower($article['title'].' '.$article['section_title'].' '.$article['excerpt']))->values();
                @endphp
                <div
                    x-data="{
                        query: '',
                        open: false,
                        texts: @js($searchIndex),
                        get results() {
                            const words = this.query.toLowerCase().split(/\s+/).filter(Boolean);
                            if (! words.length) return [];
                            const found = [];
                            for (let i = 0; i < this.texts.length && found.length < 6; i++) {
                                if (words.every(word => this.texts[i].includes(word))) found.push(i);
                            }
                            return found;
                        },
                    }"
                    @click.outside="open = false"
                    @keydown.escape="open = false"
                    class="relative mt-10 max-w-xl"
                >
                    <form role="search" @submit.prevent="if (results.length) $refs['result' + results[0]].click()" class="flex rounded-lg bg-white p-1.5 shadow-2xl shadow-navy-950/40">
                        <label for="hero-search" class="sr-only">{{ __('Search articles') }}</label>
                        <div class="relative flex-1">
                            <flux:icon name="magnifying-glass" variant="mini" class="pointer-events-none absolute top-1/2 left-4 size-5 -translate-y-1/2 text-zinc-400" />
                            <input
                                id="hero-search"
                                type="search"
                                autocomplete="off"
                                x-model="query"
                                @focus="open = true"
                                @input="open = true"
                                placeholder="{{ __('Search mortgages, roofing, tenants...') }}"
                                class="w-full rounded-md border-0 bg-transparent py-3 pr-3 pl-12 text-base text-navy-950 placeholder:text-zinc-400 focus:ring-0 focus:outline-none"
                            />
                        </div>
                        <button type="submit" class="rounded-md bg-brand-500 px-5 text-sm font-bold text-white transition hover:bg-brand-600">{{ __('Search') }}</button>
                    </form>

                    <div
                        x-cloak
                        x-show="open && query.trim() !== ''"
                        x-transition.opacity
                        class="absolute inset-x-0 top-full z-30 mt-2 flex flex-col overflow-hidden rounded-lg border border-line bg-surface shadow-2xl shadow-navy-950/30"
                    >
                        @foreach ($allArticles as $i => $article)
                            <a
                                href="{{ route('article', $article['slug']) }}"
                                x-ref="result{{ $i }}"
                                :style="{ order: results.indexOf({{ $i }}) }"
                                class="hidden border-b border-line px-5 py-3 hover:bg-soft focus:bg-soft focus:outline-none"
                                :class="{ 'hidden': ! results.includes({{ $i }}), 'block': results.includes({{ $i }}) }"
                            >
                                <span class="block text-[10px] font-extrabold tracking-[0.14em] text-brand-600 uppercase dark:text-brand-300">{{ $article['section_title'] }}</span>
                                <span class="mt-0.5 block text-sm font-bold text-ink">{{ $article['title'] }}</span>
                            </a>
                        @endforeach
                        <p x-show="results.length === 0" class="px-5 py-4 text-sm text-muted">{{ __('No articles found.') }}</p>
                    </div>
                </div>

                <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-navy-300">
                    <span class="flex -space-x-2">
                        @foreach ($authors->take(5) as $author)
                            @include('partials.avatar', ['author' => $author, 'class' => 'size-9 text-xs !ring-navy-900'])
                        @endforeach
                    </span>
                    <span>{!! __('Written by :count in lending, property, and construction', ['count' => '<span class="font-bold text-white">'.e(trans_choice(':count specialist|:count specialists', $authors->count())).'</span>']) !!}</span>
                </div>
            </div>

            {{-- Trending rotator --}}
            <div class="lg:col-span-5">
                <div class="rounded-xl border border-navy-700 bg-navy-950/60 p-5 backdrop-blur">
                    <p class="flex items-center gap-2 text-[11px] font-extrabold tracking-[0.22em] text-zest-400 uppercase">
                        <flux:icon name="fire" variant="mini" class="size-4 text-brand-400" />
                        {{ __('Trending now') }}
                    </p>

                    <div
                        x-data="{ active: 0, count: {{ $heroSlides->count() }}, go(i) { this.active = (i + this.count) % this.count } }"
                        class="hero-rotator mt-4"
                    >
                        <div class="grid grid-cols-1">
                            @foreach ($heroSlides as $s => $article)
                                <a
                                    href="{{ route('article', $article['slug']) }}"
                                    wire:navigate
                                    class="group col-start-1 row-start-1 flex min-w-0 flex-col transition-all duration-500 ease-out"
                                    @if ($s !== 0) x-cloak @endif
                                    :class="active === {{ $s }} ? 'visible translate-x-0 opacity-100' : 'invisible translate-x-3 opacity-0 pointer-events-none'"
                                    :aria-hidden="active !== {{ $s }}"
                                    :tabindex="active === {{ $s }} ? 0 : -1"
                                >
                                    <span class="relative flex aspect-[16/10] items-center justify-center overflow-hidden rounded-lg">
                                        @include('partials.article-art', ['iconClass' => 'size-14'])
                                        <span class="tag absolute top-3 left-3">{{ $article['section_title'] }}</span>
                                    </span>
                                    <span class="mt-4 line-clamp-2 font-display text-xl leading-snug font-bold text-white group-hover:text-zest-300">{{ $article['title'] }}</span>
                                    <span class="mt-2 line-clamp-2 text-sm leading-relaxed text-navy-300">{{ $article['excerpt'] }}</span>
                                </a>
                            @endforeach
                        </div>

                        @if ($heroSlides->count() > 1)
                            <div x-cloak class="mt-5 flex items-center gap-3">
                                <button type="button" @click="go(active - 1)" class="flex size-8 items-center justify-center rounded-md border border-navy-700 text-navy-300 transition hover:border-zest-400 hover:text-white" aria-label="{{ __('Previous') }}">
                                    <flux:icon name="chevron-left" variant="mini" class="size-4" />
                                </button>
                                <span class="block h-1 flex-1 overflow-hidden rounded bg-navy-800">
                                    <span
                                        x-effect="active; $el.classList.remove('is-running'); void $el.offsetWidth; $el.classList.add('is-running')"
                                        @animationend="go(active + 1)"
                                        class="hero-timer block h-full bg-zest-400"
                                    ></span>
                                </span>
                                <span class="text-xs font-bold text-navy-300 tabular-nums"><span x-text="active + 1">1</span> / {{ $heroSlides->count() }}</span>
                                <button type="button" @click="go(active + 1)" class="flex size-8 items-center justify-center rounded-md border border-navy-700 text-navy-300 transition hover:border-zest-400 hover:text-white" aria-label="{{ __('Next') }}">
                                    <flux:icon name="chevron-right" variant="mini" class="size-4" />
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Topic tiles, overlapping the hero --}}
    <section class="relative z-10 mx-auto -mt-24 max-w-7xl px-6 lg:px-8">
        <div class="grid overflow-hidden rounded-xl border border-line bg-surface shadow-xl shadow-navy-950/10 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($categories as $i => $category)
                <a href="{{ route('section', $category['id']) }}" wire:navigate class="group relative flex flex-col border-line p-7 transition hover:bg-navy-900 max-lg:[&:nth-child(-n+2)]:border-b sm:[&:nth-child(odd)]:border-r lg:border-r lg:last:border-r-0">
                    <span class="flex items-center justify-between">
                        <span class="flex size-12 items-center justify-center rounded-lg bg-brand-50 text-brand-600 transition group-hover:bg-brand-500 group-hover:text-white dark:bg-brand-900/40 dark:text-brand-300">
                            <flux:icon name="{{ $category['icon'] }}" class="size-6" />
                        </span>
                        <span class="font-display text-3xl font-extrabold text-line transition group-hover:text-navy-700">0{{ $i + 1 }}</span>
                    </span>
                    <h2 class="mt-6 font-display text-xl font-bold text-ink transition group-hover:text-white">{{ $category['title'] }}</h2>
                    @if ($category['description'])
                        <p class="mt-2 text-sm leading-relaxed text-muted transition group-hover:text-navy-300">{{ $category['description'] }}</p>
                    @endif
                    <span class="mt-6 flex items-center gap-2 text-sm font-bold text-brand-600 transition group-hover:text-zest-400 dark:text-brand-300">
                        {{ trans_choice(':count article|:count articles', $category['count']) }}
                        <flux:icon name="arrow-right" variant="mini" class="size-4 transition group-hover:translate-x-1" />
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Editor's pick: featured cover + side list --}}
    @if ($featuredArticle)
        <section id="latest" class="mx-auto max-w-7xl scroll-mt-36 px-6 pt-20 lg:px-8">
            <div class="flex flex-col justify-between gap-6 md:flex-row md:items-end">
                <div>
                    <span class="eyebrow">{{ __('Fresh from the editors') }}</span>
                    <h2 class="mt-3 font-display text-4xl font-extrabold tracking-tight text-ink">{{ __('Latest guides') }}</h2>
                </div>
                <a href="{{ route('articles') }}" wire:navigate class="btn-ghost shrink-0">
                    {{ __('View all articles') }}
                    <flux:icon name="arrow-right" variant="mini" class="size-4" />
                </a>
            </div>

            <div class="mt-10 grid gap-8 lg:grid-cols-12">
                <div class="lg:col-span-8">
                    @include('partials.article-card', ['article' => $featuredArticle, 'variant' => 'featured'])
                </div>
                <div class="flex flex-col divide-y divide-line rounded-xl border border-line bg-surface lg:col-span-4">
                    @foreach ($sideArticles as $n => $article)
                        <a href="{{ route('article', $article['slug']) }}" wire:navigate class="group flex flex-1 gap-4 p-6">
                            <span class="font-display text-4xl leading-none font-extrabold text-brand-500/80">{{ $n + 1 }}</span>
                            <span class="min-w-0">
                                <span class="text-[10px] font-extrabold tracking-[0.14em] text-muted uppercase">{{ $article['section_title'] }}</span>
                                <span class="mt-1.5 line-clamp-3 block font-display text-lg leading-snug font-bold text-ink decoration-zest-400 decoration-[3px] underline-offset-4 group-hover:underline">{{ $article['title'] }}</span>
                                <span class="mt-2 block text-xs text-muted">{{ $article['author_info']['name'] }} &middot; {{ __(':minutes min read', ['minutes' => $article['reading_minutes']]) }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            @if ($latestArticles->isNotEmpty())
                <div class="mt-16 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($latestArticles as $article)
                        @include('partials.article-card', ['article' => $article])
                    @endforeach
                </div>
            @endif
        </section>
    @else
        <section class="mx-auto max-w-7xl px-6 lg:px-8">
            @include('partials.empty-state')
        </section>
    @endif

    {{-- Topic columns: newest three per topic --}}
    <section class="mt-24 border-y border-line bg-surface">
        <div class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
            <div class="max-w-2xl">
                <span class="eyebrow">{{ __('Browse by topic') }}</span>
                <h2 class="mt-3 font-display text-4xl font-extrabold tracking-tight text-balance text-ink">{{ __('Everything your home needs, in one place') }}</h2>
            </div>

            <div class="mt-12 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($topicColumns as $column)
                    <div>
                        <a href="{{ route('section', $column['id']) }}" wire:navigate class="group flex items-center justify-between border-b-[3px] border-ink pb-3">
                            <span class="font-display text-lg font-bold text-ink">{{ $column['title'] }}</span>
                            <flux:icon name="arrow-up-right" variant="mini" class="size-4 text-muted transition group-hover:text-brand-500" />
                        </a>
                        <ul class="divide-y divide-line">
                            @foreach ($column['articles'] as $article)
                                <li>
                                    <a href="{{ route('article', $article['slug']) }}" wire:navigate class="group block py-4">
                                        <span class="line-clamp-2 font-semibold leading-snug text-body group-hover:text-brand-600 dark:group-hover:text-brand-300">{{ $article['title'] }}</span>
                                        <span class="mt-1 block text-xs text-muted">{{ __(':minutes min read', ['minutes' => $article['reading_minutes']]) }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How we work --}}
    <section class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <span class="eyebrow">{{ __('How we work') }}</span>
                <h2 class="mt-3 font-display text-4xl font-extrabold tracking-tight text-balance text-ink">{{ __('Built like a good inspection report') }}</h2>
                <p class="mt-5 leading-relaxed text-body">{{ __('Every guide lays out the facts, the costs, and the trade-offs so you can compare options and make your own call.') }}</p>
            </div>

            @php
                $steps = [
                    ['icon' => 'clipboard-document-check', 'title' => __('Researched'), 'description' => __('Grounded in public data, lender and regulator guidance, and established industry practice.')],
                    ['icon' => 'wrench-screwdriver', 'title' => __('Practical'), 'description' => __('Real costs, checklists, and questions to ask — not theory for its own sake.')],
                    ['icon' => 'scale', 'title' => __('Independent'), 'description' => __('Informational content only — never personalized financial, legal, or construction advice.')],
                ];
            @endphp
            <ol class="grid gap-px overflow-hidden rounded-xl border border-line bg-line sm:grid-cols-3 lg:col-span-7">
                @foreach ($steps as $i => $step)
                    <li class="bg-surface p-7">
                        <span class="flex size-11 items-center justify-center rounded-lg bg-navy-900 text-zest-300">
                            <flux:icon name="{{ $step['icon'] }}" class="size-5" />
                        </span>
                        <h3 class="mt-6 font-display text-xl font-bold text-ink">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-muted">{{ $step['description'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Team CTA --}}
    <section class="px-4 pb-20 sm:px-6 lg:px-8">
        <div class="relative mx-auto grid max-w-7xl overflow-hidden rounded-2xl bg-brand-600 lg:grid-cols-2">
            <div class="relative px-8 py-14 sm:px-14 lg:py-20">
                <span class="inline-flex items-center gap-2.5 text-[11px] font-extrabold tracking-[0.22em] text-zest-300 uppercase before:size-2.5 before:rotate-45 before:bg-zest-300">{{ __('Meet the team') }}</span>
                <h2 class="mt-5 font-display text-4xl leading-tight font-extrabold text-balance text-white sm:text-5xl">
                    {{ __('Written by people who know homes inside and out') }}
                </h2>
                <p class="mt-5 max-w-lg leading-relaxed text-brand-50">
                    {{ __('Our editors bring hands-on experience in mortgage lending, property management, and the building trades.') }}
                </p>
                <a href="{{ route('team') }}" wire:navigate class="btn-zest mt-9">
                    {{ __('Meet the full team') }}
                    <flux:icon name="arrow-right" variant="mini" class="size-4" />
                </a>
            </div>

            <div class="grid grid-cols-2 gap-px bg-brand-700/60 p-px">
                @foreach ($authors->take(6) as $author)
                    <div class="flex items-center gap-3 bg-brand-600 p-5">
                        @include('partials.avatar', ['author' => $author, 'class' => 'size-12 text-sm !ring-brand-400'])
                        <span class="min-w-0">
                            <span class="block truncate font-bold text-white">{{ $author['name'] }}</span>
                            <span class="line-clamp-2 text-xs text-brand-100">{{ $author['role'] }}</span>
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
