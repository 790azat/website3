@extends('layouts.site')
@use('App\Support\SiteContent')

@php
    $siteName = config('app.name', 'Laravel');
    $title = __('Our Editorial Team');
    $description = __('Meet the :site team — writers and specialists covering home financing, property management, home repairs, and contractors.', ['site' => $siteName]);

    $team = SiteContent::authors();

    $services = [
        ['icon' => 'banknotes', 'title' => __('Mortgages & Home Equity'), 'description' => __('Mortgage rates, loan programs, refinancing, HELOCs, and the true cost of financing a home.')],
        ['icon' => 'building-office-2', 'title' => __('Rentals & Landlording'), 'description' => __('Tenant screening, leases, rent collection, and running rental property as a business.')],
        ['icon' => 'wrench-screwdriver', 'title' => __('Repairs & Maintenance'), 'description' => __('Roofs, plumbing, HVAC, foundations, and the upkeep that protects a home’s value.')],
        ['icon' => 'clipboard-document-check', 'title' => __('Hiring Contractors'), 'description' => __('Bids, licenses, contracts, permits, and managing a renovation from estimate to final walkthrough.')],
        ['icon' => 'home-modern', 'title' => __('Remodeling & Upgrades'), 'description' => __('Kitchens, baths, additions, and energy upgrades, with realistic budgets and return on investment.')],
        ['icon' => 'chart-bar', 'title' => __('Property Investment'), 'description' => __('Home economics, cash flow, and weighing a property as both a place to live and an asset.')],
    ];

    $principles = [
        ['title' => __('Clear'), 'text' => __('We explain complex subjects without unnecessary jargon or misleading claims.')],
        ['title' => __('Transparent'), 'text' => __('Our content is based on publicly available information, research, and established concepts.')],
        ['title' => __('Balanced'), 'text' => __('Where it matters, we discuss benefits, challenges, and trade-offs so you can weigh them yourself.')],
    ];
@endphp

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden border-b border-line">
        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(var(--color-line)_1px,transparent_1px),linear-gradient(90deg,var(--color-line)_1px,transparent_1px)] [background-size:32px_32px] [mask-image:linear-gradient(to_left,black,transparent_70%)]"></div>

        <div class="relative mx-auto grid max-w-7xl items-center gap-14 px-6 py-16 lg:grid-cols-2 lg:px-8 lg:py-24">
            <div>
                <span class="eyebrow">{{ __('Our Editorial Team') }}</span>
                <h1 class="mt-5 font-display text-5xl leading-[1.04] font-bold tracking-tight text-balance text-ink sm:text-6xl">
                    {{ __(':site Editorial Team', ['site' => $siteName]) }}
                </h1>
                <p class="mt-7 text-lg leading-relaxed text-body">
                    {{ __('The :site editorial team brings together writers and specialists with experience in mortgage lending, property management, home maintenance, remodeling, and construction. Our contributors focus on clear, practical explanations, combining research with real-world experience to help readers understand costs, requirements, and trade-offs before they borrow, rent, repair, or hire.', ['site' => $siteName]) }}
                </p>
                <a href="#team" class="btn-primary mt-9">
                    {{ __('Meet the editors') }}
                    <flux:icon name="arrow-down" variant="mini" class="size-4" />
                </a>
            </div>

            <div class="grid grid-cols-2 gap-4">
                @foreach ($team->take(4) as $i => $member)
                    <div @class([
                        'rounded-xl p-6',
                        'bg-navy-900 text-white' => $i === 0 || $i === 3,
                        'bg-surface border border-line' => $i === 1 || $i === 2,
                        'translate-y-6' => $i % 2 === 1,
                    ])>
                        @include('partials.avatar', ['author' => $member, 'class' => 'size-14 text-base'])
                        <p @class(['mt-5 font-display text-lg font-bold', 'text-white' => $i === 0 || $i === 3, 'text-ink' => $i === 1 || $i === 2])>{{ $member['name'] }}</p>
                        <p @class(['text-sm', 'text-navy-300' => $i === 0 || $i === 3, 'text-muted' => $i === 1 || $i === 2])>{{ $member['role'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Mission --}}
    <section class="bg-navy-900">
        <div class="mx-auto max-w-5xl px-6 py-20 text-center lg:px-8">
            <span class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.16em] text-zest-300 uppercase">{{ __('Our mission') }}</span>
            <p class="mt-6 font-display text-3xl leading-snug font-bold text-balance text-white sm:text-4xl">
                &ldquo;{{ __('To give homeowners, buyers, and landlords clear, practical information they can apply to real decisions about the places they live in and invest in.') }}&rdquo;
            </p>
        </div>
    </section>

    {{-- What we cover --}}
    <section class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
        <div class="max-w-2xl">
            <span class="eyebrow">{{ __('What we cover') }}</span>
            <h2 class="mt-4 font-display text-4xl font-bold tracking-tight text-ink">{{ __('Educational resources across six areas') }}</h2>
        </div>

        <div class="mt-12 grid gap-px overflow-hidden rounded-xl border border-line bg-line sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($services as $service)
                <div class="group bg-surface p-8 transition hover:bg-soft">
                    <span class="flex size-12 items-center justify-center rounded-lg bg-navy-900 text-zest-300 transition group-hover:bg-brand-500 group-hover:text-white">
                        <flux:icon name="{{ $service['icon'] }}" class="size-6" />
                    </span>
                    <h3 class="mt-6 font-display text-xl font-bold text-ink">{{ $service['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted">{{ $service['description'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Team grid --}}
    <section id="team" class="scroll-mt-28 border-y border-line bg-surface">
        <div class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <span class="eyebrow">{{ __('The editors') }}</span>
                <h2 class="mt-4 font-display text-4xl font-bold tracking-tight text-ink">{{ __('Experience you can learn from') }}</h2>
                <p class="mt-4 leading-relaxed text-body">
                    {{ __('Our writers and analysts bring experience across consumer banking, credit, lending, wealth planning, and small-business finance.') }}
                </p>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($team as $member)
                    <div class="flex flex-col rounded-xl border border-line bg-paper p-7">
                        @include('partials.avatar', ['author' => $member, 'class' => 'size-20 text-xl'])
                        <h3 class="mt-6 font-display text-xl font-bold text-ink">{{ $member['name'] }}</h3>
                        <p class="mt-1 text-sm font-semibold text-brand-700 dark:text-brand-300">{{ $member['role'] }}</p>
                        @if (! empty($member['bio']))
                            <p class="mt-4 text-sm leading-relaxed text-muted">{{ $member['bio'] }}</p>
                        @endif
                        @if ($member['count'])
                            <p class="mt-auto pt-5 text-xs font-bold tracking-wide text-brand-700 uppercase dark:text-brand-300">
                                {{ trans_choice(':count article|:count articles', $member['count']) }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Approach --}}
    <section class="mx-auto grid max-w-7xl gap-14 px-6 py-20 lg:grid-cols-2 lg:px-8">
        <div>
            <span class="eyebrow">{{ __('Our approach') }}</span>
            <h2 class="mt-4 font-display text-4xl font-bold tracking-tight text-balance text-ink">{{ __('Information should be easy to evaluate') }}</h2>
            <p class="mt-5 leading-relaxed text-body">
                {{ __('We present information in context rather than treating individual decisions in isolation — a mortgage, a lease, or a repair bid is examined alongside its costs, risks, and long-term trade-offs.') }}
            </p>
            <p class="mt-4 leading-relaxed text-body">
                {{ __('Articles focus on explaining concepts, identifying important considerations, and helping readers understand how different choices can affect homeowners, buyers, and landlords.') }}
            </p>
        </div>

        <div class="space-y-4">
            @foreach ($principles as $i => $principle)
                <div class="flex gap-5 rounded-xl border border-line bg-surface p-6">
                    <span class="font-display text-3xl font-bold text-brand-500">0{{ $i + 1 }}</span>
                    <div>
                        <h3 class="font-display text-xl font-bold text-ink">{{ $principle['title'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-muted">{{ $principle['text'] }}</p>
                    </div>
                </div>
            @endforeach
            <p class="rounded-xl bg-zest-200 p-6 text-sm leading-relaxed text-brand-900">
                <span class="font-bold">{{ __('Please note:') }}</span> {{ __('our content is intended for educational and informational purposes and should not be considered personalized financial, investment, tax, legal, or professional advice.') }}
            </p>
        </div>
    </section>

    {{-- CTA --}}
    <section class="px-4 pb-20 sm:px-6 lg:px-8">
        <div class="relative mx-auto max-w-7xl overflow-hidden rounded-2xl bg-navy-900 px-8 py-16 text-center sm:px-14">
            <div class="absolute -bottom-24 -left-16 size-80 rounded-full border-[40px] border-brand-500/25"></div>
            <h2 class="relative font-display text-4xl font-bold text-balance text-white">{{ __('Thank you for learning with :site', ['site' => $siteName]) }}</h2>
            <p class="relative mx-auto mt-5 max-w-2xl leading-relaxed text-navy-300">
                {{ __('We will keep developing educational resources designed to make complex subjects easier to understand and evaluate.') }}
            </p>
            <a href="{{ route('articles') }}" wire:navigate class="btn-zest relative mt-9">
                {{ __('Explore our articles') }}
                <flux:icon name="arrow-right" variant="mini" class="size-4" />
            </a>
        </div>
    </section>
@endsection
