<?php

Route::group([
    'middleware' => ['web'],
    'namespace' => '\Webup\LaravelHelium\Redirection\Http\Controllers',
], function () {

    Route::group([
        'prefix' => 'admin',
        'as' => helium_route_name('tools.'),
        'namespace' => "Admin"
    ], function () {

        Route::group(['middleware' => 'admin.auth:admin'], function () {
            Route::group(['prefix' => 'redirections', 'as' => 'redirection.'], function () {
                //Read
                Route::get('/', 'IndexController@index')->name('index');
                Route::get('/datatable', 'IndexController@datatable')->name('datatable');
                Route::delete('/clean', 'DestroyController@destroyAll')->name('destroyAll');

                //Create
                Route::get('/create', 'CreateController@create')->name('create');
                Route::post('/store', 'CreateController@store')->name('store');
                Route::get('/import', 'CreateController@import')->name('import');
                Route::post('/import', 'CreateController@postImport')->name('postImport');

                //Update
                Route::get('/edit/{id}', 'EditController@edit')->name('edit');
                Route::post('/update/{id}', 'EditController@update')->name('update');

                //Delete
                Route::delete('/{id}', 'DestroyController@destroy')->name('destroy');
            });
        });
    });
});
