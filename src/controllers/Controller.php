<?php namespace Webarq\Site;

use Auth;

class Controller extends \Controller {

	protected $layout = 'layouts.master';
	
	public function __construct()
	{
		// Log out admin session, if any
		if (Auth::check() && Auth::user()->admin()->count())
		{
			Auth::logout();
		}
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = \View::make($this->layout);
		}
	}

}