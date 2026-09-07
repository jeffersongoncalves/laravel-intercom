<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Intercom\Facades\Intercom;

it('lists articles with pagination', function () {
    Http::fake(['*/articles*' => Http::response(['data' => [['id' => '1']]])]);

    $result = Intercom::articles()->list();

    expect($result['data'][0]['id'])->toBe('1');
    Http::assertSent(fn ($request) => str_contains((string) $request->url(), 'per_page=15'));
});

it('gets a single article', function () {
    Http::fake(['*/articles/1' => Http::response(['id' => '1'])]);

    $result = Intercom::articles()->get(1);

    expect($result['id'])->toBe('1');
});

it('creates an article', function () {
    Http::fake(['*/articles' => Http::response(['id' => '1'])]);

    Intercom::articles()->create('How to reset your password', 123, 'published', 'Steps...');

    Http::assertSent(fn ($request) => $request['title'] === 'How to reset your password'
        && $request['author_id'] === 123
        && $request['state'] === 'published'
        && $request['body'] === 'Steps...');
});

it('creates an article without a body', function () {
    Http::fake(['*/articles' => Http::response(['id' => '1'])]);

    Intercom::articles()->create('Draft article', 123);

    Http::assertSent(fn ($request) => $request['state'] === 'draft' && ! array_key_exists('body', $request->data()));
});

it('updates an article', function () {
    Http::fake(['*/articles/1' => Http::response(['id' => '1', 'title' => 'Updated'])]);

    $result = Intercom::articles()->update(1, ['title' => 'Updated']);

    expect($result['title'])->toBe('Updated');
});

it('deletes an article', function () {
    Http::fake(['*/articles/1' => Http::response([])]);

    Intercom::articles()->delete(1);

    Http::assertSent(fn ($request) => $request->method() === 'DELETE');
});
