<?php

namespace Webarq\Site\Support\Facades;

use Illuminate\Support\Facades\Facade;

class ResourceHandler extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'site\resourcehandler';
    }
}
