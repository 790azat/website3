<?php

use App\Support\SiteContent;

test('returns a successful response', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
});

test('hero card rotates through articles', function () {
    // Articles with cover images when there are any, otherwise older articles.
    $article = SiteContent::articles()->first(fn ($article) => $article['image'])
        ?? SiteContent::articles()->slice(10)->first();

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('hero-rotator', false)
        ->assertSee(route('article', $article['slug']), false);
});

test('homepage has an article search', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('id="hero-search"', false)
        ->assertSee('x-ref="result0"', false);
});

test('disclaimer page is linked from the footer', function () {
    $this->get(route('disclaimer'))
        ->assertOk()
        ->assertSee('Costs, Rates, and Regulations Vary')
        ->assertSee('Homeledger.site makes no representations');

    $this->get(route('home'))
        ->assertOk()
        ->assertSee(route('disclaimer'), false)
        ->assertSee('Loan terms, rates, costs, and regulations vary by lender, location, and property.');
});

test('homepage lists the latest guides', function () {
    $response = $this->get(route('home'))->assertOk()->assertSee('Latest guides');

    foreach (SiteContent::articles()->take(4) as $article) {
        $response->assertSee(route('article', $article['slug']), false);
    }
});
