<?php

namespace Hilmy\FeatureControl\ServiceProviders;

use Hilmy\FeatureControl\Facades\Feature;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FeatureControl extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Feature::class, function ($app) {
        });
        $this->app->alias(Feature::class, 'feature');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootBlade();
    }

    public function bootBlade()
    {
        Blade::if('feature', function (string $name) {
            return Feature::check($name, Auth::id());
        });
    }
}
