<?php

namespace Lennord\FloridaySdk;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Lennord\FloridaySdk\Commands\GenerateFloridayTokenCommand;

class FloridaySdkServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('floriday-sdk')
            ->hasConfigFile('floriday-sdk.php')
            ->hasCommand(GenerateFloridayTokenCommand::class);
    }
}
