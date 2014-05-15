<?php namespace Webarq\Site;

class User extends \User {

	public function getRememberToken()
	{
		return $this->remember_token;
	}

	public function setRememberToken($value)
	{
		$this->remember_token  = $value;
	}

	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	// We shouldn't make this because Admin is a different package
	public function admin()
	{
		return $this->hasOne('\Webarq\Admin\User');
	}
	
}