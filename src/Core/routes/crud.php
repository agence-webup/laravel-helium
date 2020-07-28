<?php

Route::group([
    'middleware' => ['web'],
    'namespace' => '\Webup\LaravelHelium\Core\Http\Controllers\Crud',
    'prefix' => 'crud',
    'as' => 'crud.',
], function () {
    Route::get('/migration', 'MigrationController@index')->name('index');
    Route::post('/migration', 'MigrationController@post')->name('post');
});
