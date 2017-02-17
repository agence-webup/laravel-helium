
# laravel-helium::Legals

Dependencies

- composer:
```php
"anlutro/l4-settings": "^0.4.9" (https://github.com/anlutro/laravel-settings)
```

- npm/yarn:
```javascript
"froala-editor": "^2.4.1"
"jquery": "^3.1.1"
```

Provider

```php
'providers' => [
    Webup\LaravelHelium\Legals\LegalsServiceProvider::class,
    anlutro\LaravelSettings\ServiceProvider::class,
],
```

Routes

```php
//Web
Route::get('/legals', '\Webup\LaravelHelium\Legals\Http\Controllers\LegalsController@index')->name('legals');

//Admin
Route::get('/admin/legals', '\Webup\LaravelHelium\Legals\Http\Controllers\Admin\LegalsController@edit')->name('admin.legals.edit');
Route::post('/admin/legals', '\Webup\LaravelHelium\Legals\Http\Controllers\Admin\LegalsController@update')->name('admin.legals.update');
```

Menu

```php
<a href="{{ route('admin.legals.edit') }}" class="{{ current_class('admin.legals', 'is-active') }}"><i class="fa fa-gavel icon"></i> Mentions légales</a>
```

Publish views

```bash
$ php artisan vendor:publish --tag=helium.legals
```
