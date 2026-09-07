<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Intercom\Facades\Intercom;

it('lists admins', function () {
    Http::fake(['*/admins' => Http::response(['admins' => [['id' => '1']]])]);

    $result = Intercom::admins()->list();

    expect($result['admins'][0]['id'])->toBe('1');
});

it('gets a single admin', function () {
    Http::fake(['*/admins/1' => Http::response(['id' => '1'])]);

    $result = Intercom::admins()->get(1);

    expect($result['id'])->toBe('1');
});
