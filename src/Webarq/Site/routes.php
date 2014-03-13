<?php

// Hit the URL of this route (on dev) to generate controller routes which will be written in /app/config/c_routes.php
Route::get('generate-c-routes', function()
{
	$file = app_path().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'c_routes.php';
	@unlink($file);
	$handle = fopen($file, 'w');
	fwrite($handle, "<?php\r\n");
	fwrite($handle, "return array(\r\n");

	$_files = File::allFiles(app_path().DIRECTORY_SEPARATOR.'controllers');
	$routes = '';
	foreach ($_files as $_file)
	{
		$file = str_replace(app_path().DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR, '', $_file);
		$controller = str_replace('.php', '', $file);

		$route = str_replace(DIRECTORY_SEPARATOR, '/', strtolower(str_replace('Controller', '', $controller)));
		$route = (strpos($route, 'admin/') !== 0)
			? $route = str_plural($route)
			: str_replace('admin/', 'admin-cp/', $route);

		fwrite($handle, "\t'".$route."' => '".$controller."',\r\n");

		$routes .= $route.' ('.$controller.')<br/>';
	}

	fwrite($handle, "); // Generated at ".date('Y-m-d H:i:s'));
	return $routes;
});