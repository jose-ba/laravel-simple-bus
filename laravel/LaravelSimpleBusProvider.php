<?php

declare(strict_types=1);

namespace LaravelSimpleBus;

use Buses\Domain\EventBus;
use Buses\Infrastructure\LaravelSimpleEventsBus;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelSimpleBusProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-simple-bus')
            ->hasConfigFile()
            ->hasViews();
    }

    public function register(): void
    {

    }

    public function boot(): void
    {
        $this->app->singleton(EventBus::class, LaravelSimpleEventsBus::class);
    }
}
