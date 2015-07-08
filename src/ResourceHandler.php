<?php

namespace Webarq\Site;

class ResourceHandler
{
    public $perPage = 10;
    public $resource;
    public $searchableFields = [];

    /**
    * Allow client to override the standard pagination.
    * @return void
    */
    public function customPagination()
    {
        if (\Input::get('page'))
            return;

        // Offset
        if (\Input::get('offset'))
            $this->resource = $this->resource->skip(\Input::get('offset'));

        // Limit
        if (\Input::get('limit'))
            $this->resource = $this->resource->take(\Input::get('limit'));
    }

    public function searching()
    {
        foreach ($this->searchableFields as $field) {
            if (\Input::get($field)) {
                $this->resource = $this->resource->where($field, \Input::get($field));
            }
        }
    }

    public function sorting()
    {
        if ( ! \Input::get('sort'))
            return;

        // Extract from "+field1,+field2,-field3"
        $sortFields = explode(',', \Input::get('sort'));

        foreach ($sortFields as $sortField) {

            // Get "+", "-"
            $sign = substr($sortField, 0, 1);

            // Get the field name
            $field = substr($sortField, 1);

            // Validate sort sign (plus or minus sign), a blank space means plus
            if ( ! in_array($sign, [' ', '+', '-']))
                continue;

            // Prepare the sort type
            $sortType = ($sign === ' ' || $sign === '+') ? 'asc' : 'desc';

            // Do the sort
            $this->resource = $this->resource->orderBy($field, $sortType);
        }
    }
}
