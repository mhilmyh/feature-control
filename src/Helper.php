<?php

namespace Hilmy\FeatureControl;

use Illuminate\Support\Facades\Blade;

class Helper
{
    public static function registerBlade(): void
    {
        Blade::if('feature', function (string $name) {
            return true;
        });
    }
}
