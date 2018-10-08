<?php

Route::group([
    'middleware' => ['web'],
    'namespace' => $this->namespace . '\Admin',
    'prefix' => 'admin',
    'as' => 'admin.',
], function () {
    Route::get('/login', '\Webup\LaravelHelium\Core\Http\Controllers\AuthController@showLoginForm')->name('login');
    Route::post('/login', '\Webup\LaravelHelium\Core\Http\Controllers\AuthController@login')->name('postLogin');
    Route::post('/logout', '\Webup\LaravelHelium\Core\Http\Controllers\AuthController@logout')->name('logout');

    Route::group(['middleware' => 'auth:admin'], function () {
        Route::get('', function () {
            return redirect()->route('admin.contact.index');
        })->name('home');
    });
});
