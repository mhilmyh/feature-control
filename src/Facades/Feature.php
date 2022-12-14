<?php

namespace Hilmy\FeatureControl\Facades;

use Hilmy\FeatureControl\Manager;
use Illuminate\Support\Facades\Facade;

class Feature extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Manager::class;
    }
}
