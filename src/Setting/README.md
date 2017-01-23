# laravel-helium


Provider

```php
'providers' => [
    Webup\LaravelHelium\Setting\SettingServiceProvider::class,
    anlutro\LaravelSettings\ServiceProvider::class,
],
```

Routes

```php
Route::get('/admin/settings', '\Webup\LaravelHelium\Setting\Http\Controllers\SettingController@edit')->name('admin.setting.edit');
Route::post('/admin/settings', '\Webup\LaravelHelium\Setting\Http\Controllers\SettingController@update')->name('admin.setting.update');
```

Menu

```php
<a href="{{ route('admin.setting.edit') }}" class="{{ current_class('admin.setting', 'is-active') }}"><i class="fa fa-cog icon"></i> Paramètres</a>
```

Publish views

```bash
php artisan vendor:publish --tag=helium.setting
```

Dependencies

"anlutro/l4-settings": "^0.4.9" https://github.com/anlutro/laravel-settings
