<?php

namespace Webarq\Site\Providers;

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
        //
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
