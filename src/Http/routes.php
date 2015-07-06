<?php
Route::group(['prefix' => 'api'], function() {
    Route::resource('settings', 'Webarq\Site\Http\Controllers\Api\SettingController');
});
