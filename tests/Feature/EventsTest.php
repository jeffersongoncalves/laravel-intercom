<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Intercom\Facades\Intercom;

it('creates an event', function () {
    Http::fake(['*/events' => Http::response([])]);

    Intercom::events()->create('invited-friend', 'user1', 1700000000, ['invitee' => 'jane']);

    Http::assertSent(fn ($request) => $request['event_name'] === 'invited-friend'
        && $request['user_id'] === 'user1'
        && $request['created_at'] === 1700000000
        && $request['metadata']['invitee'] === 'jane');
});

it('defaults created_at to now when omitted', function () {
    Http::fake(['*/events' => Http::response([])]);

    Intercom::events()->create('invited-friend', 'user1');

    Http::assertSent(fn ($request) => is_int($request['created_at']));
});

it('lists events for a user', function () {
    Http::fake(['*/events*' => Http::response(['events' => [['id' => '1']]])]);

    $result = Intercom::events()->list('user1', 10);

    expect($result['events'][0]['id'])->toBe('1');
    Http::assertSent(fn ($request) => str_contains((string) $request->url(), 'type=user')
        && str_contains((string) $request->url(), 'user_id=user1')
        && str_contains((string) $request->url(), 'per_page=10'));
});
