<?php

namespace Webarq\Site\Api;

use EllipseSynergie\ApiResponse\Laravel\Response as EllipseSynergieResponse;

class Response extends EllipseSynergieResponse
{
    public $transformer;

    public function destroy($id)
    {
        $item = \Site\ResourceHandler::getResource()->find($id);

        // 404
        if ( ! $item)
            return $this->errorNotFound();

        $item->delete();
        return response()->json([], 200);
    }

    public function index()
    {
        // Search
        \Site\ResourceHandler::searching();

        // 404
        if (\Site\ResourceHandler::getResource()->count() == 0)
            return $this->errorNotFound();

        // Sorting
        \Site\ResourceHandler::sorting();

        // Custom pagination
        \Site\ResourceHandler::customPagination();

        $resource = \Site\ResourceHandler::getResource();
        if (\Input::get('offset') || \Input::get('limit')) {
            return $this->withCollection($resource->get(), $this->transformer);
        } else {
            $resource = $resource->paginate(\Site\ResourceHandler::getPerPage());
            return $this->withPaginator($resource, $this->transformer);
        }
    }

    public function setTransformer($transformer)
    {
        $this->transformer = $transformer;
    }

    public function show($id)
    {
        $item = \Site\ResourceHandler::getResource()->find($id);

        // 404
        if ( ! $item)
            return $this->errorNotFound();

        return $this->withItem($item, $this->transformer);
    }
}