<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Intercom\Facades\Intercom;

it('lists companies with pagination', function () {
    Http::fake(['*/companies*' => Http::response(['data' => [['id' => '1']]])]);

    $result = Intercom::companies()->list();

    expect($result['data'][0]['id'])->toBe('1');
    Http::assertSent(fn ($request) => str_contains((string) $request->url(), 'per_page=15'));
});

it('gets a single company', function () {
    Http::fake(['*/companies/1' => Http::response(['id' => '1'])]);

    $result = Intercom::companies()->get(1);

    expect($result['id'])->toBe('1');
});

it('creates a company', function () {
    Http::fake(['*/companies' => Http::response(['id' => '1'])]);

    Intercom::companies()->create('company-42', ['name' => 'Acme']);

    Http::assertSent(fn ($request) => $request['company_id'] === 'company-42' && $request['name'] === 'Acme');
});

it('updates a company', function () {
    Http::fake(['*/companies/1' => Http::response(['id' => '1', 'name' => 'Acme Inc'])]);

    $result = Intercom::companies()->update(1, ['name' => 'Acme Inc']);

    expect($result['name'])->toBe('Acme Inc');
});
