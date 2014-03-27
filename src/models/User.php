<?php namespace Webarq\Site;

class User extends \User {

	public function role()
	{
		return $this->belongsTo('\Webarq\Site\User\Role');
	}
	
}