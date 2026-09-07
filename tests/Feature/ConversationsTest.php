<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Intercom\Facades\Intercom;

it('lists conversations with pagination', function () {
    Http::fake(['*/conversations*' => Http::response(['conversations' => [['id' => '1']]])]);

    $result = Intercom::conversations()->list();

    expect($result['conversations'][0]['id'])->toBe('1');
    Http::assertSent(fn ($request) => str_contains((string) $request->url(), 'per_page=15'));
});

it('gets a single conversation', function () {
    Http::fake(['*/conversations/1' => Http::response(['id' => '1'])]);

    $result = Intercom::conversations()->get(1);

    expect($result['id'])->toBe('1');
});

it('searches conversations', function () {
    Http::fake(['*/conversations/search' => Http::response(['conversations' => [['id' => '1']]])]);

    $result = Intercom::conversations()->search('state', 'open');

    expect($result['conversations'][0]['id'])->toBe('1');
    Http::assertSent(fn ($request) => $request['query']['field'] === 'state' && $request['query']['value'] === 'open');
});

it('replies to a conversation', function () {
    Http::fake(['*/conversations/1/reply' => Http::response(['id' => '1'])]);

    Intercom::conversations()->reply(1, 'Thanks!', 'admin1');

    Http::assertSent(fn ($request) => $request['message_type'] === 'comment'
        && $request['type'] === 'admin'
        && $request['admin_id'] === 'admin1'
        && $request['body'] === 'Thanks!');
});

it('closes a conversation', function () {
    Http::fake(['*/conversations/1/parts' => Http::response(['id' => '1'])]);

    Intercom::conversations()->close(1, 'admin1', 'Resolved');

    Http::assertSent(fn ($request) => $request['message_type'] === 'close'
        && $request['admin_id'] === 'admin1'
        && $request['body'] === 'Resolved');
});
