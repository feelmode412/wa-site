<?php

namespace Webarq\Site;

class ResourceHandler
{
    public $offset = null;
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

        if ($this->offset !== null)
            $this->resource = $this->resource->skip($this->offset)->take($this->perPage);

        // Offset
        if (\Input::get('offset'))
            $this->resource = $this->resource->skip(\Input::get('offset'));

        // Limit
        if (\Input::get('limit'))
            $this->resource = $this->resource->take(\Input::get('limit'));
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getPerPage()
    {
        return $this->perPage;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function searching()
    {
        foreach ($this->searchableFields as $field) {
            if (\Input::get($field)) {
                $this->resource = $this->resource->where($field, \Input::get($field));
            }
        }
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
    }

    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    public function setSearchableFields($searchableFields = [])
    {
        $this->searchableFields = $searchableFields;
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
