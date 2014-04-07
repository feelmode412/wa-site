<?php namespace Webarq\Site;

class User extends \User {

	// We shouldn't make this because Admin is a different package
	public function admin()
	{
		return $this->hasOne('\Webarq\Admin\User');
	}
	
}