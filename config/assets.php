<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Assets Manifest
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default asset manifest that should be used. You
    | can think of a manifest as a set of bundles or compilations. Most sites
    | will only have a single manifest to be used on their frontend.
    |
    */

    'default' => 'frontend',

    /*
    |--------------------------------------------------------------------------
    | Assets Manifests
    |--------------------------------------------------------------------------
    |
    | Manifests contain lists of assets that are referenced by static keys that
    | point to dynamic locations, such as a cache-busted location. We currently
    | support two types of manifests:
    |
    | assets: key-value pairs to match assets to their revved counterparts
    |
    | bundles: a series of entrypoints for loading bundles
    |
    */

    'manifests' => [
        'frontend' => [
            'path' => public_path(),
            'url' => env('FRONTEND_URL', '/'),
            'assets' => public_path('mix-manifest.json'),
        ]
    ]
];
