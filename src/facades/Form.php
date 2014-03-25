<?php namespace Site;
use Illuminate\Support\Facades\Facade;
class Form extends Facade {
	protected static function getFacadeAccessor()
	{
		return '\Webarq\Site\Form';
	}
}