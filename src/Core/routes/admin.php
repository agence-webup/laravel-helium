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

        // {{ Helium Crud }}
        // Don't remove previous line if you are using larave-helium crud generator

        //Admin User
        Route::group(['prefix' => 'admins', 'as' => 'admin_user.', 'namespace' => "\Webup\LaravelHelium\Core\Http\Controllers\Admin"], function () {
            //Read
            Route::group(["middleware" => "admin.can:admin_users.read"], function () {
                Route::get('/', 'AdminUserController@index')->name('index');
                Route::get('/datatable', 'AdminUserController@datatable')->name('datatable');
            });
            //Create
            Route::group(["middleware" => "admin.can:admin_users.create"], function () {
                Route::get('/create', 'AdminUserController@create')->name('create');
                Route::post('/store', 'AdminUserController@store')->name('store');
            });
            //Update
            Route::group(["middleware" => "admin.can:admin_users.update"], function () {
                Route::get('/{id}/edit', 'AdminUserController@edit')->name('edit');
                Route::post('/{id}/update', 'AdminUserController@update')->name('update');
            });
            //Delete
            Route::group(["middleware" => "admin.can:admin_users.delete"], function () {
                Route::post('/{id}/destroy', "AdminUserController@destroy")->name('destroy');
            });
        });

        //Role
        Route::group(['prefix' => 'roles', 'as' => 'role.', 'namespace' => "\Webup\LaravelHelium\Core\Http\Controllers\Admin"], function () {
            //Read
            Route::group(["middleware" => "admin.can:roles.read"], function () {
                Route::get('/', 'RoleController@index')->name('index');
                Route::get('/datatable', 'RoleController@datatable')->name('datatable');
            });
            //Create
            Route::group(["middleware" => "admin.can:roles.create"], function () {
                Route::get('/create', 'RoleController@create')->name('create');
                Route::post('/store', 'RoleController@store')->name('store');
            });
            //Update
            Route::group(["middleware" => "admin.can:roles.update"], function () {
                Route::get('/{id}/edit', 'RoleController@edit')->name('edit');
                Route::post('/{id}/update', 'RoleController@update')->name('update');
            });
            //Delete
            Route::group(["middleware" => "admin.can:roles.delete"], function () {
                Route::post('/{id}/destroy', "RoleController@destroy")->name('destroy');
            });
        });
    });
});
