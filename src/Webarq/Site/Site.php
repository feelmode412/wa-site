<?php namespace Webarq\Site;

class Site {

	public function getCss()
	{
		return \View::make('css');
	}

	public function getJs()
	{
		return \View::make('js');
	}

}