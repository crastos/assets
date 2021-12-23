<?php

namespace Roots\Acorn\Assets;

use Roots\Acorn\Assets\View\BladeDirectives;
use Illuminate\Support\ServiceProvider;

class AssetsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('assets', function () {
            return new Manager($this->app, $this->app->make('config')->get('assets'));
        });

        $this->app->singleton('assets.manifest', function ($app) {
            return $app['assets']->manifest($this->getDefaultManifest());
        });

        $this->app->alias('assets.manifest', \Roots\Acorn\Assets\Manifest::class);
        $this->app->alias('assets', \Illuminate\Foundation\Mix::class);

        $this->mergeConfigFrom(dirname(__DIR__) . "/config/assets.php", 'assets');

        $this->publishes([
            dirname(__DIR__) . "/config/assets.php" => $this->app->basePath('config') . "/assets.php"
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->bound('view')) {
            $this->registerDirectives();
        }
    }

    protected function registerDirectives()
    {
        $compiler = $this->app->make('view')->getEngineResolver()->resolve('blade')->getCompiler();

        (new BladeDirectives($compiler))->register();
    }

    protected function getDefaultManifest()
    {
        return $this->app->make('config')->get('assets.default');
    }
}
