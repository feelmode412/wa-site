<?php

namespace Webarq\Site\Api;

use EllipseSynergie\ApiResponse\Laravel\Response as EllipseSynergieResponse;

class Response extends EllipseSynergieResponse
{
    public $resource;
    public $transformer;

    public function index()
    {
        $resource = $this->resource;

        // Search
        $resource->searching();

        // 404
        if ($resource->resource->count() == 0)
            return $this->errorNotFound();

        // Sorting
        $resource->sorting();

        // Custom pagination
        $resource->customPagination();

        $resourceModel = $resource->resource;
        if (\Input::get('offset') || \Input::get('limit')) {
            return $this->withCollection($resourceModel->get(), $this->transformer);
        } else {
            $resourceModel = $resourceModel->paginate($resource->perPage);
            return $this->withPaginator($resourceModel, $this->transformer);
        }
    }

    public function show($id)
    {
        $item = $this->resource->resource->find($id);

        // 404
        if ( ! $item)
            return $this->errorNotFound();

        return $this->withItem($item, $this->transformer);
    }
}
