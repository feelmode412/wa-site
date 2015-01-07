<?php namespace Webarq\Site;

use Auth;

class Controller extends \Controller {

	protected $layout = 'layouts.master';
	
	public function __construct()
	{
		// Log out admin session, if any
		if (Auth::check() && Auth::user()->admin()->count())
		{
			\Session::put('siteMessage', array(
				'content' => 'Please use another browser to view the website/frontend to continue working with this admin panel.',
				'type' => 'warning',
			));
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