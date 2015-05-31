<?php namespace Webarq\Site;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GenerateControllerRoutes extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'site:generate-c-routes';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate controller routes.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
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

			// Output to console
			$this->info($route.' => '.$controller);
		}

		fwrite($handle, ');');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
	}

}
