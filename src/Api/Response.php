<?php

namespace Webarq\Site\Api;

use EllipseSynergie\ApiResponse\Laravel\Response as EllipseSynergieResponse;

/**
* The API Response Class
*
* @package Webarq\Site
* @author Qosdil A. <qosdil@gmail.com>
* @todo create prepareItem() or something to refactor several methods
*/
class Response extends EllipseSynergieResponse
{

    const CODE_CONFLICT = 'GEN-CONFLICT';

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
        if (\Input::get('offset') || \Input::get('limit') || \Site\ResourceHandler::getOffset() !== null) {
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

    public function store()
    {
        $resource = \Site\ResourceHandler::getResource();
        $status = true;
        try {
            $item = $resource->create(\Input::all());
        }
        catch (\Exception $e) {
            $status = false;
            $message = \App::environment() === 'production' ? '' : $e->getMessage();
        }

        if ( ! $status)
            return $this->setStatusCode(409)->withError($message, self::CODE_CONFLICT);

        return $this->withItem($item, $this->transformer);
    }

    public function update($id)
    {
        $item = \Site\ResourceHandler::getResource()->find($id);

        // 404
        if ( ! $item)
            return $this->errorNotFound();

        foreach (\Input::all() as $field => $value) {

            // Skip unknown fields
            if ( ! $item->{$field})
                continue;

            $item->{$field} = $value;
        }

        $status = true;
        try {
            $item->update();
        }
        catch (\Exception $e) {
            $status = false;
            $message = (\App::environment() === 'production') ? parent::CODE_WRONG_ARGS : $e->getMessage();
        }

        if ( ! $status)
            return $this->errorWrongArgs($message);

        return $this->withItem($item, $this->transformer);
    }
}
