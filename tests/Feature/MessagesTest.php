<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Intercom\Facades\Intercom;

it('creates a message', function () {
    Http::fake(['*/messages' => Http::response(['id' => '1'])]);

    $result = Intercom::messages()->create('Hello there', 'admin1', 'user1');

    expect($result['id'])->toBe('1');
    Http::assertSent(fn ($request) => $request['message_type'] === 'inapp'
        && $request['body'] === 'Hello there'
        && $request['from']['id'] === 'admin1'
        && $request['to']['id'] === 'user1');
});
