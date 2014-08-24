<?php namespace Webarq\Site;

class Site {

	public function generateControllerRoutes()
	{
		$cRouteFile = app_path().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'c_routes.php';
		@unlink($cRouteFile);
		$handle = fopen($cRouteFile, 'w');
		fwrite($handle, "<?php\r\n");
		fwrite($handle, "return array(\r\n");

		$_files = \File::allFiles(app_path().DIRECTORY_SEPARATOR.'controllers');
		$adminUrlPrefix = (\Config::get('admin::admin.urlPrefix')) ?: 'admin-cp';
		foreach ($_files as $_file)
		{
			$file = str_replace(app_path().DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR, '', $_file);
			$controller = str_replace('.php', '', $file);

			$route = str_replace(DIRECTORY_SEPARATOR, '/', strtolower(str_replace('Controller', '', $controller)));
			if ($route === 'base') continue;
			if ($route === 'admin' || substr($route, 0, 6) === 'admin/')
			{
				$route = substr_replace($route, $adminUrlPrefix, 0, 5);
			}
			elseif ( ! in_array($route, array('home')))
			{
				$route = str_plural($route);
			}

			// Replace slash (generated in Mac or Linux) with backslash
			$controller = str_replace('/', '\\', $controller);
			
			fwrite($handle, "\t'".$route."' => '".$controller."',\r\n");
		}

		fwrite($handle, "); // Generated at ".date('Y-m-d H:i:s'));

		header('Content-type: text/text');
		die(file_get_contents($cRouteFile));
	}

	public function registerControllerRoutes()
	{
		foreach (\Config::get('c_routes') as $route => $controller)
		{
			\Route::controller($route, $controller);
		}
	}
	
}