<?php

namespace Webarq\Site\Http\Controllers\Api;

use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use Webarq\Site\Http\Controllers\Controller;
use Webarq\Site\Models\Setting;
use Webarq\Site\Transformers\SettingTransformer;

class SettingController extends Controller
{
    public function __construct(Response $response, Setting $settings)
    {
        $this->response = $response;
        $this->settings = $settings;
    }

    public function index2()
    {
        return $this->settings->all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $settings = $this->settings->all();
        return $this->response->withCollection($settings, new SettingTransformer);
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
