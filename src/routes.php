<?php

Route::get('generate-c-routes', function()
{
	return Site::generateControllerRoutes();
});