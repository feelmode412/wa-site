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

	public function handleUpload($inputName, $prefix, $row = null, $resizeWidth = null, $resizeHeight = null, $ratio = true)
	{
		if (\Input::hasFile($inputName))
		{
			$path = public_path('contents').'/';
			$file = \Input::file($inputName);
			if (strlen($prefix) > 16)
			{
				throw new \Exception('Prefix length may not be more than 16.');
			}

			$fileName = $prefix.'-'.\Str::random().'.'.$file->getClientOriginalExtension();
			if ($resizeWidth || $resizeHeight)
			{
				\Image::make($file->getRealPath())->resize($resizeWidth, $resizeHeight, $ratio)->save($path.$fileName);
			}
			else
			{
				$file->move($path, $fileName);	
			}
			
			if ($row && $row->{$inputName} && substr($row->{$inputName}, 0, 8) !== 'default-')
			{
				// Use "@" in case the file is missing
				@unlink($path.$row->{$inputName});
			}
			
			return $fileName;
		}
		elseif ($row) // File not uploaded by user on Edit mode
		{
			return $row->{$inputName};
		}
		else // File not uploaded by user on Add mode
		{
			return null;
		}
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

	public function generateJs()
	{
		$setting = Setting::ofCodeType('minify_js', 'system')->value;
		$minifyJs = (strtolower($setting) === 'yes') ? true : false;
		$output = '';
		if ($minifyJs)
		{
			$output .= '<script type="text/javascript">'."\r\n";
			$minifier = new Minify\Js();
			foreach (Config::get('site::js_files') as $file)
			{
				$file = public_path('js/'.$file.'.js');
				if (file_exists($file))
					$minifier->add($file);
			}

			$output .= $minifier->minify();
			$output .= "\r\n</script>";
		}
		else
		{
			foreach (Config::get('site::js_files') as $file)
			{
				$output .= '<script type="text/javascript" src="'.asset('js/'.$file.'.js').'"></script>'."\r\n";
			}
		}

		return $output;
	}
	
}