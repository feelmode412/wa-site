<?php namespace Webarq\Site;

class Controller extends \Controller {
	
	protected $ajaxResponse = array();
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
	
	public function __destruct()
	{
		if ($this->ajaxResponse)
		{
			// Let's prevent requests without 'xmlhttprequest' on production environment
			if (\App::environment() === 'production' && ! \Request::ajax())
			{
				\App::abort(403);
			}
			
			return \Response::json($this->ajaxResponse);
		}
	}

}