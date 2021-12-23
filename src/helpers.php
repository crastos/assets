<?php

namespace Roots;

use Roots\Acorn\Assets\Asset\Asset;
use Roots\Acorn\Assets\Bundle;

use function \app;

/**
 * Get asset from manifest
 *
 * @param  string $asset
 * @return Asset
 */
function asset(string $asset): Asset
{
    return app('assets.manifest')->asset($asset);
}

/**
 * Get bundle from manifest
 *
 * @param  string $bundle
 * @return Bundle
 */
function bundle(string $bundle = 'app'): Bundle
{
    return app('assets.manifest')->bundle($bundle);
}

/**
 *
 * @param string $bundle
 * @param bool $defer
 * @param bool $async
 * @return mixed
 */
function scripts(string $bundle = 'app', bool $defer = false, bool $async = false)
{
    return bundle($bundle)->scripts($defer, $async);
}

/**
 *
 * @param string $bundle
 * @param string $media
 * @return mixed
 */
function styles(string $bundle = 'app', $media = 'all')
{
    return bundle($bundle)->styles($media);
}
