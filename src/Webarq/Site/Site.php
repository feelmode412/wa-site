<?php namespace Webarq\Site;

// Laravel's
use Config, Mail, Route;

// Packages
use MatthiasMullie\Minify;

class Site {

	public function appendCurrentUrl($options = array())
	{
		parse_str($_SERVER['QUERY_STRING'], $parsedStr);
		$queryStrings = array_merge($parsedStr, $options);

		return \URL::current().'?'.http_build_query($queryStrings);
	}

	public function currencyFormat($number, $currency = 'IDR')
	{
		return $currency.' '.number_format($number, 2, '.', ',');
	}


	/**
	* lang()
	* Locale helper for database content
	* For example: returns the field "title_locale_id" when the locale session
	* is "id"
	*
	*/
	public function lang($row, $fieldName)
	{
		$locale = \App::getLocale();

		return ($locale === 'en')
			? $row->{$fieldName}
			: ($row->{$fieldName.'_locale_'.$locale}) ?: $row->{$fieldName};
	}

	public function listLang($fieldName)
	{
		$locale = \App::getLocale();
		return ($locale === 'en') ? $fieldName : $fieldName.'_locale_'.$locale;
	}

	public function generateControllerRoutes()
	{
		$cRouteFile = app_path().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'c_routes.php';
		@unlink($cRouteFile);
		$handle = fopen($cRouteFile, 'w');
		fwrite($handle, "<?php\r\n");
		fwrite($handle, "return array(\r\n");

		$_files = \File::allFiles(app_path().DIRECTORY_SEPARATOR.'controllers');
		$adminUrlPrefix = (Config::get('admin::admin.urlPrefix')) ?: 'admin-cp';
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
		foreach (Config::get('c_routes') as $route => $controller)
		{
			$adminUrlPrefix = (Config::get('admin::admin.urlPrefix')) ?: 'admin-cp';
			if (substr($route, 0, strlen($adminUrlPrefix)) == $adminUrlPrefix)
			{
				Route::group(array('before' => 'admin_auth'), function() use ($route, $controller)
				{
					Route::controller($route, $controller);
				});
			}
			else
			{
				Route::controller($route, $controller);
			}
		}
	}

	/**
	* @todo Multilanguage for the header and footer
	*
	*/
	public function sendEmail($templateCode, $receiver, $contentVars = array())
	{
		$emailTemplate = Email\Template::whereCode($templateCode)->first();
		$content = Setting::ofCodeType('header', 'email')->value
			.$emailTemplate->content
			.Setting::ofCodeType('footer', 'email')->value;
		$content = str_replace('{email}', $receiver->email, $content);
		$content = str_replace('{asset}', asset(null), $content);
		
		foreach ($contentVars as $var => $replacement)
		{
			$content = str_replace($var, $replacement, $content);
		}

		return Mail::send('site::layouts.email.master', array('content' => $content), function($message) use ($emailTemplate, $receiver)
		{
			$message->from(Setting::ofCodeType('email', 'noreply')->value, Setting::ofCodeType('name', 'noreply')->value);
			$message->to($receiver->email, $receiver->email);
			$message->subject($emailTemplate->title);
		});
	}

	/**
	* generateJs()
	*
	* @todo $minifiedJs option (true or false)
	*/
	public function generateJs()
	{
		$setting = Setting::ofCodeType('static_js', 'system')->value;
		$staticJs = (strtolower($setting) === 'yes') ? true : false;
		$output = '';
		if ($staticJs)
		{
			foreach (Config::get('site::js_files') as $file)
			{
				$output .= '<script type="text/javascript" src="'.asset('js/'.$file.'.js').'"></script>'."\r\n";
			}
		}
		else
		{
			$output .= '<script type="text/javascript">';
			$minifier = new Minify\Js();
			foreach (Config::get('site::js_files') as $file)
			{
				$file = public_path('js/'.$file.'.js');
				if (file_exists($file))
					$minifier->add($file);
			}

			$output .= $minifier->minify();
			$output .= '</script>';
		}

		return $output;
	}
	
}