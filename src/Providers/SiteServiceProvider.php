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

        $loader = AliasLoader::getInstance();
        $loader->alias('Site\Form', 'Webarq\Site\Support\Facades\Form');

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'site');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['site'] = $this->app->share(function($app)
        {
            return new \Webarq\Site\Site();
        });
    }
}
