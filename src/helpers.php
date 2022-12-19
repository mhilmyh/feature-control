<?php

use Hilmy\FeatureControl\Facades\Feature;

if (!function_exists('feature')) {
    /**
     * Access the feature facade or check is feature on
     * 
     * @return mixed
     */
    function feature(string $name = '', mixed $id = null): mixed
    {
        if (func_num_args() == 0) {
            return app(Feature::class);
        }

        return app(Feature::class)->check($name, $id);
    }
}
