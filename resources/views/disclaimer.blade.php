@extends('layouts.site')

@php
    $siteName = config('app.name', 'Laravel');
    $siteDomain = ucfirst(\App\Support\SiteContent::domain());
    $title = __('Disclaimer');
    $description = __('Read the :site disclaimer: our content is educational only and is not professional financial, legal, tax, or construction advice.', ['site' => $siteName]);

    $lastUpdated = \Carbon\Carbon::parse('2026-09-26');

    $sections = [
        [
            'heading' => __('Educational Purposes Only'),
            'body' => __('The content provided on :domain is for informational and educational purposes only and should not be construed as professional financial, legal, tax, or construction advice. The creators and editors of this site are not licensed financial advisors, attorneys, or contractors.', ['domain' => $siteDomain]),
        ],
        [
            'heading' => __('Costs, Rates, and Regulations Vary'),
            'body' => __('Loan terms, rates, costs, and regulations vary by lender, location, and property. Before making any financing, property, or repair decision, conduct your own research and consult a qualified, licensed professional who understands your specific situation.'),
        ],
        [
            'heading' => __('No Warranties or Liability'),
            'body' => __(':domain makes no representations or warranties as to the accuracy, completeness, or suitability of the information contained herein, and assumes no liability for any losses or damages arising from the use of this content.', ['domain' => $siteDomain]),
        ],
    ];
@endphp

@section('content')
    @include('partials.legal-page', [
        'heading' => __('Disclaimer'),
        'docName' => __('this Disclaimer'),
        'closingNote' => __('This Disclaimer applies to all content published on :site, including articles and guides.', ['site' => $siteName]),
    ])
@endsection
