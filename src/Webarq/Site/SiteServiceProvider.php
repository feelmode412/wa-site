<?php namespace Webarq\Site;

use Illuminate\Support\ServiceProvider;

class SiteServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Let's do the hack a little
		\Config::set('auth.model', '\Webarq\Site\User');

		$this->package('webarq/site');
 
		\App::missing(function($exception)
		{
			return \Response::view('site::errors.missing', array(), 404);
		});

		\App::bind('site\form', function()
		{
			return new \Webarq\Site\Form();
		});
		include __DIR__.'/../../helpers.php';
		include __DIR__.'/../../routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['site'] = $this->app->share(function($app)
		{
			return new Site;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('site');
	}

}