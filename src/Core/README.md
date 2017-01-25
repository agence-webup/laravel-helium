# laravel-helium

Dependencies

- https://github.com/agence-webup/helium

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
Route::get('/admin/login', '\Webup\LaravelHelium\Core\Http\Controllers\AuthController@showLoginForm')->name('login');
Route::post('/admin/login', '\Webup\LaravelHelium\Core\Http\Controllers\AuthController@login')->name('postLogin');
Route::post('/admin/logout', '\Webup\LaravelHelium\Core\Http\Controllers\AuthController@logout')->name('logout');
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
if ($guard == 'admin') {
    return redirect()->route('admin.home');
}
```
