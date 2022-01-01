<?php

namespace Roots\Acorn\Assets;

use InvalidArgumentException;
use Illuminate\Contracts\Foundation\Application;
use Roots\Acorn\Assets\Concerns\Mixable;
use Roots\Acorn\Assets\Contracts\Manifest as ManifestContract;
use Roots\Acorn\Assets\Contracts\ManifestNotFoundException;

/**
 * Manage assets manifests
 *
 * @see \Illuminate\Support\Manager
 * @link https://github.com/illuminate/support/blob/8.x/Manager.php
 */
class Manager
{
    use Mixable;

    /**
     * Application container
     *
     * @var Application
     */
    protected $app;

    /**
     * Resolved manifests
     *
     * @var ManifestContract[]
     */
    protected $manifests;

    /**
     * Assets Config
     *
     * @var array
     */
    protected $config;

    /**
     * Initialize the AssetManager instance.
     *
     * @param Container $app
     * @param array $config
     */
    public function __construct(Application $app, $config = [])
    {
        $this->app = $app;
        $this->config = $config;
    }

    /**
     * Get the path to a versioned Mix file.
     *
     * @param  string  $path
     * @param  string  $manifestDirectory
     * @return \Illuminate\Support\HtmlString|string
     *
     * @throws \Exception
     */
    public function __invoke(string $path, string $manifestDirectory = '')
    {
        if (! $manifestDirectory) {
            return \Roots\asset($path);
        }

        if (! $manifest = is_file(public_path("{$manifestDirectory}/manifest.json"))) {
            $manifest = public_path("{$manifestDirectory}/mix-manifest.json");
        }

        return $this->manifest($manifestDirectory ?: '__mix__', [
            'path' => public_path($manifestDirectory),
            'url' => config('app.mix_url', '') . $manifestDirectory,
            'assets' => $manifest,
        ])->asset($path);
    }

    /**
     * Register the given manifest
     *
     * @param  string $name
     * @param  Manifest $manifest
     * @return static
     */
    public function register(string $name, ManifestContract $manifest): self
    {
        $this->manifests[$name] = $manifest;

        return $this;
    }

    /**
     * Get a Manifest
     *
     * @param  string $name
     * @param  array $config
     * @return ManifestContract
     */
    public function manifest(string $name, ?array $config = null): ManifestContract
    {
        $manifest = $this->manifests[$name] ?? $this->resolve($name, $config);

        return $this->manifests[$name] = $manifest;
    }

    /**
     * Resolve the given manifest.
     *
     * @param  string  $name
     * @return ManifestContract
     *
     * @throws InvalidArgumentException
     */
    protected function resolve(string $name, ?array $config): ManifestContract
    {
        $config = $config ?? $this->getConfig($name);

        if (isset($config['handler'])) {
            return new $config['handler']($config);
        }

        $url = $this->getMixHotUri($path = $config['path'])
            ?? $config['url'];
        $assets = isset($config['assets'])
            ? $this->getJsonManifest($config['assets'])
            : [];
        $bundles = isset($config['bundles'])
            ? $this->getJsonManifest($config['bundles'])
            : [];

        return new Manifest($path, $url, $assets, $bundles);
    }

    /**
     * Opens a JSON manifest file from the local file system
     *
     * @param string $jsonManifest Path to .json file
     * @return array
     */
    protected function getJsonManifest(string $jsonManifest): array
    {
        if (! file_exists($jsonManifest)) {
            throw new ManifestNotFoundException("The manifest [{$jsonManifest}] cannot be found.");
        }

        return json_decode(file_get_contents($jsonManifest), true) ?? [];
    }

    /**
     * Get the assets manifest configuration.
     *
     * @param  string  $name
     * @return array
     */
    protected function getConfig(string $name): array
    {
        return $this->config['manifests'][$name];
    }
}
