# laravel-helium

Dependencies

LaravelHelium/Setting
"yajra/laravel-datatables-oracle": "^6.22" : https://github.com/yajra/laravel-datatables

npm:
"datatables.net": "^1.10.13"
"datatables.net-dt": "^2.1.1"
"dropmic": "^0.1.5"


Provider

```php
'providers' => [
    Yajra\Datatables\DatatablesServiceProvider::class,
    Webup\LaravelHelium\Contact\ContactServiceProvider::class,
],
```

Publish migrations, views and config

```bash
$ php artisan vendor:publish --tag=helium
```

Routes

```php
// admin
Route::get('/admin/contacts', '\Webup\LaravelHelium\Contact\Http\Controllers\Admin\ContactController@index')->name('admin.contact.index');
Route::get('/admin/contacts/datatable', '\Webup\LaravelHelium\Contact\Http\Controllers\Admin\ContactController@datatable')->name('admin.contact.datatable');
Route::get('/admin/contacts/{id}', '\Webup\LaravelHelium\Contact\Http\Controllers\Admin\ContactController@show')->name('admin.contact.show');
Route::delete('/admin/contacts/{id}', '\Webup\LaravelHelium\Contact\Http\Controllers\Admin\ContactController@destroy')->name('admin.contact.destroy');

// web
Route::get('/contact', '\Webup\LaravelHelium\Contact\Http\Controllers\ContactController@form')->name('contact');
Route::post('/contact', '\Webup\LaravelHelium\Contact\Http\Controllers\ContactController@store')->name('contact.store');
```

Menu

```php
<a href="{{ route('admin.contact.index') }}" class="{{ current_class('admin.contact', 'is-active') }}"><i class="fa fa-envelope icon"></i> Contact</a>
```
