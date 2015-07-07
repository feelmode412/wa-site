<?php

namespace Webarq\Site\Http\Controllers\Api;

use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use Webarq\Site\Http\Controllers\Controller;
use Webarq\Site\Models\Setting;
use Webarq\Site\Transformers\SettingTransformer;

class SettingController extends Controller
{
    protected $perPage = 10;
    protected $searchableFields = ['code', 'type', 'value'];

    public function __construct(Response $response, Setting $settings)
    {
        $this->response = $response;
        $this->settings = $settings;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $settings = $this->settings;

        // Search
        foreach ($this->searchableFields as $field) {
            if (\Input::get($field)) {
                $settings = $settings->where($field, \Input::get($field));
            }
        }

        if ($settings->count() === 0) {
            return $this->response->errorNotFound();
        }

        // Sorting
        if (\Input::get('sort')) {

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
                $settings = $settings->orderBy($field, $sortType);
            }
        }

        // Client overrides our pagination by using "offset" and/or "limit"
        if ( ! \Input::get('page')) {

            // Offset
            if (\Input::get('offset')) {
                $settings = $settings->skip(\Input::get('offset'));
            }

            // Limit
            if (\Input::get('limit')) {
                $settings = $settings->take(\Input::get('limit'));
            }
        }

        $transformer = new SettingTransformer();
        if (\Input::get('offset') || \Input::get('limit')) {
            return $this->response->withCollection($settings->get(), $transformer);
        } else {
            $settings = $settings->paginate($this->perPage);
            return $this->response->withPaginator($settings, $transformer);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
