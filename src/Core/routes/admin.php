<?php

Route::group([
    'middleware' => ['web'],
    'namespace' => '\App\Http\Controllers\Admin',
    'prefix' => 'admin',
    'as' => 'admin.',
], function () {
    Route::get('/login', '\Webup\LaravelHelium\Core\Http\Controllers\AuthController@showLoginForm')->name('login');
    Route::post('/login', '\Webup\LaravelHelium\Core\Http\Controllers\AuthController@login')->name('postLogin');
    Route::post('/logout', '\Webup\LaravelHelium\Core\Http\Controllers\AuthController@logout')->name('logout');

    Route::group(['middleware' => 'admin.auth:admin'], function () {
        Route::get('', "PagesController@home")->name('home');

        Route::get('admins', "\Webup\LaravelHelium\Core\Http\Controllers\Admin\AdminUserController@index")->name('admin_user.index');
        Route::get('admins/datatable', "\Webup\LaravelHelium\Core\Http\Controllers\Admin\AdminUserController@datatable")->name('admin_user.datatable');
        Route::get('admins/create', "\Webup\LaravelHelium\Core\Http\Controllers\Admin\AdminUserController@create")->name('admin_user.create');
        Route::post('admins/store', "\Webup\LaravelHelium\Core\Http\Controllers\Admin\AdminUserController@store")->name('admin_user.store');
        Route::get('admins/{id}/edit', "\Webup\LaravelHelium\Core\Http\Controllers\Admin\AdminUserController@edit")->name('admin_user.edit');
        Route::post('admins/{id}/update', "\Webup\LaravelHelium\Core\Http\Controllers\Admin\AdminUserController@update")->name('admin_user.update');
        Route::post('admins/{id}/destroy', "\Webup\LaravelHelium\Core\Http\Controllers\Admin\AdminUserController@destroy")->name('admin_user.destroy');

        // {{ Helium Crud }}
        // Don't remove previous line if you are using larave-helium crud generator
    });
});
