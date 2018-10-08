# laravel-helium

Dependencies

- [helium](https://github.com/agence-webup/helium) `npm install agence-webup/helium#v3 --save`
- [dropmic](https://github.com/agence-webup/dropmic) `npm install --save dropmic@^0.3.0`
- [jquery]() `npm install --save jquery@^3.3.1`
- [datatables]() `npm install --save datatables@^1.10.18`


Publish migrations, views and translations

```bash
$ php artisan vendor:publish --tag=helium
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
