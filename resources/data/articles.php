<?php

/**
 * Site content data: sections, authors, and programs.
 *
 * There is no database; this file and the Markdown files next to it are the
 * single source of truth, read through App\Support\SiteContent.
 *
 * Articles live in resources/data/articles/{slug}.md: a front-matter block
 * (title, section, author, date, optional image/excerpt) followed by the
 * Markdown body. Images are picked up automatically from
 * public/images/articles/{slug}.{webp,jpg,jpeg,png} (or the front-matter
 * "image" path); without one, generated artwork is shown.
 *
 * Sections: 'title', 'order', optional 'icon' (Heroicon name) and
 *   'description' (shown on topic cards and the section header).
 * Authors: 'name', 'role', 'bio', optional 'photo' (file in
 *   public/images/team/). Without 'photo', public/images/team/{key}.{webp,jpg,
 *   jpeg,png} is used when present, otherwise the author's initials.
 */

return [

    'sections' => [
        'home-financing' => [
            'title' => 'Home Financing',
            'order' => 1,
            'icon' => 'banknotes',
            'description' => 'Mortgages, refinancing, home equity, and loan programs — understand the true cost of buying and borrowing.',
        ],
        'property-management' => [
            'title' => 'Property Management',
            'order' => 2,
            'icon' => 'building-office-2',
            'description' => 'Tenants, leases, rent, and operations for landlords and owners of rental property.',
        ],
        'home-repair-services' => [
            'title' => 'Home Repair Services',
            'order' => 3,
            'icon' => 'wrench-screwdriver',
            'description' => 'Roofs, plumbing, HVAC, foundations, and the repairs that keep a home safe and sound.',
        ],
        'home-contractors' => [
            'title' => 'Home Contractors',
            'order' => 4,
            'icon' => 'clipboard-document-check',
            'description' => 'Finding, vetting, and working with contractors — bids, contracts, permits, and remodels.',
        ],
    ],

    'authors' => [
        'maya-patel' => [
            'name' => 'Maya Patel',
            'role' => 'Home Financing & Real Estate Credit Analyst',
            'bio' => 'Maya Patel spent five years working as a residential mortgage loan officer before moving into financial journalism. She writes about mortgage financing, home equity, interest rates, and the lending considerations that affect homeowners and prospective buyers.',
        ],
        'chloe-dubois' => [
            'name' => 'Chloe Dubois',
            'role' => 'Home Economics & Property Investment Editor',
            'bio' => 'Chloe Dubois combines experience in real estate appraisal and consumer advocacy with a focus on homeownership and residential finance. She writes about home improvement costs, property investment, mortgage decisions, and the financial considerations that affect homeowners.',
        ],
        'david-galarza' => [
            'name' => 'David Galarza',
            'role' => 'Rental Operations & Landlord Contributor',
            'bio' => 'David Galarza has managed a small portfolio of residential rental properties for more than 10 years. His writing focuses on landlord operations, tenant management, property maintenance, rental technology, and the practical financial decisions involved in running residential investment properties.',
        ],
        'marcus-kessler' => [
            'name' => 'Marcus Kessler',
            'role' => 'Home Maintenance & Systems Specialist',
            'bio' => 'A licensed master plumber and former property maintenance supervisor, Marcus provides practical, troubleshoot-first content. He helps homeowners understand when a buzzing HVAC unit or a leaking pipe is a DIY fix and when it is time to call a professional before minor issues become expensive disasters.',
        ],
        'julian-vinter' => [
            'name' => 'Julian Vinter',
            'role' => 'Structural Repair & Exterior Specialist',
            'bio' => 'Having spent years working hands-on in exterior construction and storm-damage restoration, Julian focuses on high-stakes home investments. His work helps homeowners evaluate structural wear-and-tear, choose durable materials, and navigate insurance claims for major roof and siding replacements.',
        ],
        'elena-kovalska' => [
            'name' => 'Elena Kovalska',
            'role' => 'Home Remodeling & Contractor Relations Writer',
            'bio' => 'Elena worked as an architectural draftsperson and project coordinator for residential general contractors. She bridges the gap between homeowners and builders, offering insider advice on how to vet contractors, read complex estimates, structure payment schedules, and survive a major remodel without losing your sanity.',
        ],
    ],

    'programs' => [],

];
