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

/*
|--------------------------------------------------------------------------
| Custom Helper for Admin Panel
|--------------------------------------------------------------------------
|
| A url()-like helper for admin panel.
| 
|
*/

function admin_url($path = null)
{
	$url = $_SERVER['SCRIPT_NAME'].'/admin-cp';
	if ($path)
	{
		$url .= '/'.$path;
	}
	
	return $url;
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