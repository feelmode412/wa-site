<?php

if ( ! function_exists('append_current_url'))
{
	function append_current_url($options = array())
	{
		parse_str($_SERVER['QUERY_STRING'], $parsedStr);
		$queryStrings = array_merge($parsedStr, $options);

		return \URL::current().'?'.http_build_query($queryStrings);
	}
}
if ( ! function_exists('currency_format'))
{
	function currency_format($number, $currency = 'IDR')
	{
		return $currency.' '.number_format($number, 2, '.', ',');
	}
}

if ( ! function_exists('dd2'))
{
	function dd2($var)
	{
		if (in_array(gettype($var), array('boolean', 'NULL')))
			var_dump($var);
		else
		{
			echo '<pre>';
			print_r($var);
		}

		die;
	}
}

if ( ! function_exists('handle_upload'))
{
	function handle_upload($inputName, $prefix, $row = null, $resizeWidth = null, $resizeHeight = null, $ratio = true)
	{
		if (Input::hasFile($inputName))
		{
			$path = public_path('contents').'/';
			$file = Input::file($inputName);
			if (strlen($prefix) > 16)
			{
				throw new Exception('Prefix length may not be more than 16.');
			}

			$fileName = $prefix.'-'.Str::random().'.'.$file->getClientOriginalExtension();
			if ($resizeWidth || $resizeHeight)
			{
				Image::make($file->getRealPath())
					->resize($resizeWidth, $resizeHeight, $ratio)->save($path.$fileName);
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
}


/*
|--------------------------------------------------------------------------
| Locale Helper for Database Content
|--------------------------------------------------------------------------
|
| For example: returns the field "title_locale_id" when the locale session
| is "id"
|
*/
if ( ! function_exists('lang'))
{
	function lang($row, $fieldName)
	{
		$locale = App::getLocale();

		return ($locale === 'en')
			? $row->{$fieldName}
			: ($row->{$fieldName.'_locale_'.$locale}) ?: $row->{$fieldName};
	}
}

if ( ! function_exists('list_lang'))
{
	function list_lang($fieldName)
	{
		$locale = App::getLocale();

		return ($locale === 'en')
			? $fieldName
			: $fieldName.'_locale_'.$locale;
	}	
}