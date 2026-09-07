<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Intercom\Facades\Intercom;

it('lists tags', function () {
    Http::fake(['*/tags' => Http::response(['data' => [['id' => 'tag1']]])]);

    $result = Intercom::tags()->list();

    expect($result['data'][0]['id'])->toBe('tag1');
});

it('creates a tag', function () {
    Http::fake(['*/tags' => Http::response(['id' => 'tag1', 'name' => 'vip'])]);

    $result = Intercom::tags()->create('vip');

    expect($result['name'])->toBe('vip');
    Http::assertSent(fn ($request) => $request['name'] === 'vip');
});

it('deletes a tag', function () {
    Http::fake(['*/tags/tag1' => Http::response([])]);

    Intercom::tags()->delete('tag1');

    Http::assertSent(fn ($request) => $request->method() === 'DELETE');
});
