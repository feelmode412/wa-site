<?php
Route::group(['prefix' => 'api'], function() {

    $adminConfig = config('webarq_admin');
    Route::get('vars', function() use ($adminConfig) {
        $content = [
            'asset' => asset(null),
            'name' => config('app.name'),
            'url' => url(),
        ];

        if ($adminConfig && isset($adminConfig['uri'])) {
            $content['admin'] = [
                'asset' => asset($adminConfig['uri']),
                'url'   => url($adminConfig['uri']),
            ];
        };

        return response($content)
            ->setLastModified(new DateTime('now'))
            ->setExpires(new DateTime('tomorrow'));
    });

    Route::get('csrf-token', function() {
        return ['_token' => csrf_token()];
    });

    Route::resource('settings', 'Webarq\Site\Http\Controllers\Api\SettingController');
});
