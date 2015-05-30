<?php

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