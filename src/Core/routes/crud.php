<?php

Route::group([
    'middleware' => ['web'],
    'namespace' => '\Webup\LaravelHelium\Core\Http\Controllers\Crud',
    'prefix' => 'crud',
    'as' => 'crud.',
], function () {
    Route::group(['middleware' => 'admin.auth:admin'], function () {
        Route::get('/', 'IndexController@index')->name('index');
        Route::get('/migration', 'MigrationController@index')->name('migration.index');
        Route::post('/migration', 'MigrationController@post')->name('migration.post');
    });
});
