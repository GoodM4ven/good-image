<?php

namespace GoodM4ven\GoodImage;

use GoodM4ven\GoodImage\Components\GoodImage;
use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class GoodImageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         *
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         *
         */
        $package
            ->name('good-image')
            ->hasConfigFile()
            ->hasAssets()
            ->hasViews()
            ->hasCommand(InstallCommand::class);
    }

    public function bootingPackage()
    {
        $this->registerComponents();
    }

    protected function registerComponents()
    {
        Blade::component('good-image', GoodImage::class);
    }
}
