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
            'bio' => 'Maya spent five years working as a residential mortgage loan officer before moving into financial journalism. She specializes in mortgage products, interest-rate changes, home insurance, and practical guidance for first-time buyers and homeowners.',
        ],
        'david-galarza' => [
            'name' => 'David Galarza',
            'role' => 'Rental Operations & Landlord Contributor',
            'bio' => 'David has managed a portfolio of residential rental properties for more than 10 years. He focuses on landlord operations, tenant relationships, property maintenance, rental regulations, and the practical challenges of managing investment properties.',
        ],
        'marcus-kessler' => [
            'name' => 'Marcus Kessler',
            'role' => 'Home Maintenance & Systems Specialist',
            'bio' => 'Marcus is a licensed master plumber and former property maintenance supervisor. His expertise covers plumbing, HVAC, electrical systems, home repairs, troubleshooting, preventive maintenance, and deciding when a homeowner should call a professional.',
        ],
        'elena-kovalska' => [
            'name' => 'Elena Kovalska',
            'role' => 'Home Remodeling & Contractor Relations Writer',
            'bio' => 'Elena is a former architectural draftsperson and project coordinator who worked with residential general contractors. She specializes in remodeling projects, contractor selection, construction estimates, project planning, payment schedules, and managing renovation work.',
        ],
        'julian-vinter' => [
            'name' => 'Julian Vinter',
            'role' => 'Structural Repair & Exterior Specialist',
            'bio' => 'Julian has hands-on experience in exterior construction and storm-damage restoration. His focus includes structural wear, roofing and siding, exterior repairs, durable construction materials, storm damage, and insurance-related restoration projects.',
        ],
        'chloe-dubois' => [
            'name' => 'Chloe Dubois',
            'role' => 'Home Economics & Property Investment Editor',
            'bio' => 'Chloe has a background in real estate appraisal and consumer advocacy. She covers the financial side of homeownership, including renovation ROI, property investment, home-related debt, household costs, and strategies for building and managing residential assets.',
        ],
    ],

    'programs' => [],

];
