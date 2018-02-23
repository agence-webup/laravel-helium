# laravel-helium

Dependencies

- [helium](https://github.com/agence-webup/helium) `npm install --save helium-admin@^2.2.0`
- [dropmic](https://github.com/agence-webup/dropmic) `npm install --save dropmic@^0.3.0`
- [jquery]() `npm install --save jquery@^3.3.1`
- [datatables]() `npm install --save datatables@^1.10.13`

Provider

```php
'providers' => [
    Webup\LaravelHelium\Core\CoreServiceProvider::class,
],
```

Publish migrations, views and translations

```bash
$ php artisan vendor:publish --tag=helium
```

Routes

```php
Route::get('/login', '\Webup\LaravelHelium\Core\Http\Controllers\AuthController@showLoginForm')->name('login');
Route::post('/login', '\Webup\LaravelHelium\Core\Http\Controllers\AuthController@login')->name('postLogin');
Route::post('/logout', '\Webup\LaravelHelium\Core\Http\Controllers\AuthController@logout')->name('logout');

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('', function () {
        return redirect()->route('admin.contact.index');
    })->name('home');
});
```

RouteServiceProvider.php

```php
    /**
     * Define the "admin" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace.'\Admin',
            'prefix' => 'admin',
            'as' => 'admin.',
        ], function ($router) {
            require base_path('routes/admin.php');
        });
    }
```

config/auth.php

```
'providers' => [
    // ...
    'admins' => [
        'driver' => 'eloquent',
        'model' => Webup\LaravelHelium\Core\Entities\AdminUser::class,
    ],
],

'guards' => [
    // ...

    'admin' => [
        'driver' => 'session',
        'provider' => 'admins',
    ],
],
```

Define admin login URL :
Exception/Handle.php add into unauthenticated function

```php
if (in_array('admin', $exception->guards())) {
    return redirect()->guest(route('admin.login'));
}
```
Define redirect URL after admin login :
Middleware/RedirectIfAuthenticated.php add into handle function

```php
if (Auth::guard($guard)->check()) {
    if ($guard == 'admin') {
        return redirect()->route('admin.home');
    }
    // ...
}
```
