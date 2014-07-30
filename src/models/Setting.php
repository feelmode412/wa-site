<?php namespace Webarq\Site;
class Setting extends \Eloquent {
	
	public $timestamps = false;
	
	public function scopeOfCodeType($query, $code, $type)
	{
		return $query->whereCode($code)->whereType($type)->first();
	}

}