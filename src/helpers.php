<?php

/*
|--------------------------------------------------------------------------
| Kohana-style translation
|--------------------------------------------------------------------------
|
| See http://kohanaframework.org/3.3/guide/kohana/files/i18n
| 
|
*/
function __($key, $options = array())
{
	$t = (Config::get('i18n.'.App::getLocale().'.'.$key)) ?: $key;
	foreach ($options as $key => $value)
	{
		$t = str_replace($key, $value, $t);
	}
	return $t;
}

function append_current_url($options = array())
{
	parse_str($_SERVER['QUERY_STRING'], $parsedStr);
	$queryStrings = array_merge($parsedStr, $options);

	return \URL::current().'?'.http_build_query($queryStrings);
}

function currency_format($number, $currency = 'IDR')
{
	return $currency.' '.number_format($number, 2, '.', ',');
}

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

function handle_upload($inputName, $prefix, $model = null, $resizeWidth = null, $resizeHeight = null, $ratio = true)
{
	if (Input::hasFile($inputName))
	{
		$path = public_path('contents').'/';
		$file = Input::file($inputName);
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
		
		if ($model && $model->{$inputName} && substr($model->{$inputName}, 0, 8) !== 'default-')
		{
			// Use "@" in case the file is missing
			@unlink($path.$model->{$inputName});
		}
		
		return $fileName;
	}
	elseif ($model) // File not uploaded by user on Edit mode
	{
		return $model->{$inputName};
	}
	else // File not uploaded by user on Add mode
	{
		return null;
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

function lang($row, $fieldName)
{
	$locale = App::getLocale();

	return ($locale === 'en')
		? $row->{$fieldName}
		: ($row->{$fieldName.'_locale_'.$locale}) ?: $row->{$fieldName};
}

function list_lang($fieldName)
{
	$locale = App::getLocale();

	return ($locale === 'en')
		? $fieldName
		: $fieldName.'_locale_'.$locale;
}