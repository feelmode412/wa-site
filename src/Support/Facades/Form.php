<?php

namespace Webarq\Site\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Form extends Facade
{
    protected static function getFacadeAccessor()
    {
    	return 'site\form';
    }
}
