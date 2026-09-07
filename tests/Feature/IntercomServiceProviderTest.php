<?php

use JeffersonGoncalves\Intercom\Facades\Intercom;
use JeffersonGoncalves\Intercom\Intercom as IntercomManager;

it('merges the default config', function () {
    expect(config('intercom.api_version'))->toBe('2.11')
        ->and(config('intercom.default_per_page'))->toBe(15);
});

it('resolves the facade to the manager singleton', function () {
    expect(Intercom::getFacadeRoot())->toBeInstanceOf(IntercomManager::class);
});

it('always resolves the same manager instance', function () {
    expect(app(IntercomManager::class))->toBe(app(IntercomManager::class));
});
