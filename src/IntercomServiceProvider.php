<?php

namespace JeffersonGoncalves\Intercom;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class IntercomServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('intercom')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(Intercom::class, function () {
            return new Intercom(
                (string) config('intercom.api_key'),
                (string) config('intercom.api_version', '2.11'),
                (int) config('intercom.default_per_page', 15),
            );
        });
    }
}
