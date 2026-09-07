<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Intercom\Exceptions\IntercomException;
use JeffersonGoncalves\Intercom\Facades\Intercom;

it('lists contacts with pagination', function () {
    Http::fake([
        '*/contacts*' => Http::response(['data' => [['id' => '1', 'email' => 'jane@example.com']]]),
    ]);

    $result = Intercom::contacts()->list(['starting_after' => 'cursor123']);

    expect($result['data'][0]['email'])->toBe('jane@example.com');

    Http::assertSent(fn ($request) => str_contains((string) $request->url(), '/contacts?')
        && str_contains((string) $request->url(), 'per_page=15')
        && str_contains((string) $request->url(), 'starting_after=cursor123')
        && $request->hasHeader('Authorization', 'Bearer test-api-key')
        && $request->hasHeader('Intercom-Version', '2.11'));
});

it('gets a single contact', function () {
    Http::fake(['*/contacts/1' => Http::response(['id' => '1'])]);

    $result = Intercom::contacts()->get(1);

    expect($result['id'])->toBe('1');
});

it('creates a contact', function () {
    Http::fake(['*/contacts' => Http::response(['id' => '1'], 200)]);

    $result = Intercom::contacts()->create(['email' => 'jane@example.com']);

    expect($result['id'])->toBe('1');
    Http::assertSent(fn ($request) => $request['email'] === 'jane@example.com' && $request['role'] === 'user');
});

it('requires an email to create a contact', function () {
    Intercom::contacts()->create([]);
})->throws(InvalidArgumentException::class, 'The "email" attribute is required.');

it('updates a contact', function () {
    Http::fake(['*/contacts/1' => Http::response(['id' => '1', 'name' => 'Jane'])]);

    $result = Intercom::contacts()->update(1, ['name' => 'Jane']);

    expect($result['name'])->toBe('Jane');
});

it('searches contacts', function () {
    Http::fake(['*/contacts/search' => Http::response(['data' => [['id' => '1']]])]);

    $result = Intercom::contacts()->search('email', 'jane@example.com');

    expect($result['data'][0]['id'])->toBe('1');
    Http::assertSent(fn ($request) => $request['query']['field'] === 'email'
        && $request['query']['operator'] === '='
        && $request['query']['value'] === 'jane@example.com');
});

it('deletes a contact', function () {
    Http::fake(['*/contacts/1' => Http::response([])]);

    Intercom::contacts()->delete(1);

    Http::assertSent(fn ($request) => $request->method() === 'DELETE');
});

it('tags a contact', function () {
    Http::fake(['*/contacts/1/tags' => Http::response(['id' => 'tag1'])]);

    Intercom::contacts()->tag(1, 'tag1');

    Http::assertSent(fn ($request) => $request['id'] === 'tag1' && $request->method() === 'POST');
});

it('untags a contact', function () {
    Http::fake(['*/contacts/1/tags/tag1' => Http::response([])]);

    Intercom::contacts()->untag(1, 'tag1');

    Http::assertSent(fn ($request) => $request->method() === 'DELETE');
});

it('throws an IntercomException on a failed request', function () {
    Http::fake(['*/contacts/1' => Http::response(['type' => 'error.list', 'errors' => [['code' => 'not_found', 'message' => 'Contact not found']]], 404)]);

    Intercom::contacts()->get(1);
})->throws(IntercomException::class, 'Contact not found');
