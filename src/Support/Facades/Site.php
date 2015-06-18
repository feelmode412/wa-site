<?php

namespace Webarq\Site\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Site extends Facade
{
    protected static function getFacadeAccessor()
    {
    	return 'site';
    }
}
