<?php

namespace Hilmy\FeatureControl;

use Hilmy\FeatureControl\Facades\Feature;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FeatureControlServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::if('feature', function (string $name) {
            return Feature::check($name, Auth::id());
        });
    }
}
