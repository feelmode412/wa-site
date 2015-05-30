# WEBARQ Site

A Laravel 4 package contains stuff regularly used in WEBARQ's web application projects.

See also: WEBARQ\Presence.

## Included Packages
- Intervention/Image v1.5.0

## Installation

### Basic

1. Make sure that your SSH public key has been registered on the private repository server.
2. Add the private repository to `composer.json`:

		"repositories": [
			{
				"type": "vcs",
				"url": "git@[server-name]:webarq/site.git"
			}
		]
3. Add the dependency:

		"require": {
			"webarq/site": "1.3.x-dev"
		}
4. Change `preferred-install` to `auto`:

		"config": {
			"preferred-install": "auto"
		},
5. Update Composer:

		"composer update webarq/site -vv"
6. Update your `/app/config/app.php`:
		
		'providers' => array(
			'Intervention\Image\ImageServiceProvider',
			'Webarq\Site\SiteServiceProvider',
		);

		'aliases' => array(
			'Image' => 'Intervention\Image\Facades\Image',
			'Site' => 'Webarq\Site\SiteFacade',
		);

### Run Migrations

	php artisan migrate --package="webarq/site"
		
### Register The Controller Route Generator

In your `/app/routes.php`, add the following line on top of others:
	
	Site::registerControllerRoutes();

### Call The Javascript Standard Helpers (Optional)

You may need to add the scripts to your `layouts.master.php` file:

	{{ View::make('site::js_helpers') }}

## Usages
### Controller Routes Generator

After creating controllers, generate their routes by hitting the generator URL like below: 

	http://domain.com/index.php/generate-c-routes

Then you can see the results in `/app/config/c_routes.php`.

 

&copy; 2014 [Web Architect Technology](http://www.webarq.com/)