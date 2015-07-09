<?php
Route::group(['prefix' => 'api'], function() {
    Route::get('csrf-token', function() {
        return ['_token' => csrf_token()];
    });

    Route::resource('settings', 'Webarq\Site\Http\Controllers\Api\SettingController');
});
