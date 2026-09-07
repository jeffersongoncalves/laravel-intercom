<?php

namespace JeffersonGoncalves\Intercom\Tests;

use JeffersonGoncalves\Intercom\IntercomServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            IntercomServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('intercom.api_key', 'test-api-key');
        $app['config']->set('intercom.api_version', '2.11');
    }
}
