<?php

namespace Webarq\Site\Http\Controllers\Api;

use Webarq\Site\Http\Controllers\Controller;
use Webarq\Site\Models\Setting;
use Webarq\Site\ResourceHandler;
use Webarq\Site\Api\Response;
use Webarq\Site\Transformers\SettingTransformer;

class SettingController extends Controller
{
    public function __construct(Response $response, Setting $settings)
    {
        $this->response = $response;

        // Set the resource from model
        \Site\ResourceHandler::setResource($settings);

        // Provide transformer
        $this->response->setTransformer(new SettingTransformer());
    }

    public function index()
    {
        \Site\ResourceHandler::setSearchableFields(['code', 'type', 'value']);
        return $this->response->index();
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
        return $this->response->show($id);
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
