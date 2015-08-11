# WEBARQ Site


A Laravel 5 package contains stuff regularly used in WEBARQ's web application projects.

## Included Packages
- Cartalyst/Sentinel v2.0.x
- Illuminate/Support v5.1.x
- Intervention/Image v2.2.x

## Installation

### Basic

1. Make sure that your SSH public key has been registered on the private repository server.
2. Add the private repository to `composer.json`. For example:

		"repositories": [
			{
				"type": "vcs",
				"url": "git@[server-name]:webarq/site.git"
			}
		]
3. Add the dependency:

		"require": {
			"webarq/site": "2.0.x-dev"
		}
4. Update Composer:

		"composer update webarq/site -vv"
5. Update your `/config/app.php`:
		
		'providers' => [
			// Others...
			
			// Additionals
			Cartalyst\Sentinel\Laravel\SentinelServiceProvider::class,
			Illuminate\Html\HtmlServiceProvider::class,
			Webarq\Site\Providers\SiteServiceProvider::class,
		];

		'aliases' => [
			// Others...
		
			// Additionals
			'Form' => Illuminate\Html\FormFacade::class,
			'HTML' => Illuminate\Html\HtmlFacade::class,
			'Site' => Webarq\Site\Support\Facades\Site::class,
			
			// Additionals for Sentinel
			'Activation' => Cartalyst\Sentinel\Laravel\Facades\Activation::class,
			'Reminder'   => Cartalyst\Sentinel\Laravel\Facades\Reminder::class,
			'Sentinel'   => Cartalyst\Sentinel\Laravel\Facades\Sentinel::class,
		];
		
### Publish Migrations
	php artisan vendor:publish --provider="Webarq\Site\Providers\SiteServiceProvider" --tag="migrations"

### Run Migrations

	php artisan migrate

### Call The Javascript Standard Helpers (Optional)

You may need to add the scripts to your `layouts.master.php` file:

	{{ View::make('site::js_helpers') }}
 
***
&copy; 2014 - 2015 [Web Architect Technology, PT](http://www.webarq.com/)