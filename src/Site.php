<?php namespace Webarq\Site;

// Laravel's
use Config, Mail, Route;

// Packages
use Intervention\Image\ImageManager;

class Site {

	public function appendCurrentUrl($options = array())
	{
		parse_str($_SERVER['QUERY_STRING'], $parsedStr);
		$queryStrings = array_merge($parsedStr, $options);

		return \URL::current().'?'.http_build_query($queryStrings);
	}

	public function handleUpload($inputName, $prefix, $row = null, $resizeWidth = null, $resizeHeight = null, \Closure $callback = null)
	{
		if (\Input::hasFile($inputName))
		{
			$path = public_path('contents').'/';
			$file = \Input::file($inputName);
			if (strlen($prefix) > 16)
			{
				throw new \Exception('Prefix length may not be more than 16.');
			}

			$fileName = $prefix.'-'.str_random().'.'.$file->getClientOriginalExtension();
			if ($resizeWidth || $resizeHeight)
			{
				$imageManager = new ImageManager();
				$imageManager->make($file->getRealPath())->resize($resizeWidth, $resizeHeight, $callback)->save($path.$fileName);
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
	
}