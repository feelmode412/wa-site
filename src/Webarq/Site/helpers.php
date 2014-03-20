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
| Clean Environment Detection
|--------------------------------------------------------------------------
|
| Create a file under / directory, name it [server_name].env.txt.
| Then write down your environment name in it. For example: "local", "test-server"
| Don't forget to Git-exclude the file.
| 
*/
function detect_env()
{

	$envFile = gethostname().'.env.txt';
	if (file_exists('index.php') && file_exists('../'.$envFile)) // Dev, web
	{
		return file_get_contents('../'.$envFile);
	}
	elseif (file_exists($envFile)) // Dev, CLI (artisan)
	{
		return file_get_contents($envFile);
	}
	else // Prod
	{
		return 'production';
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
		
		if ($model && $model->{$inputName})
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