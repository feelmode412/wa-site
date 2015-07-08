<?php

namespace Webarq\Site\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class SiteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../database/migrations/' => database_path('/migrations')
        ], 'migrations');

        $this->app->singleton('site\form', function ($app) {
            return new \Webarq\Site\Form();
        });

        $this->app->singleton('site\resourcehandler', function($app) {
            return new \Webarq\Site\ResourceHandler();
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('Site\Form', 'Webarq\Site\Support\Facades\Form');
        $loader->alias('Site\ResourceHandler', 'Webarq\Site\Support\Facades\ResourceHandler');

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'site');

        require __DIR__.'/../Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['site'] = $this->app->share(function ($app) {
            return new \Webarq\Site\Site();
        });
    }
}
