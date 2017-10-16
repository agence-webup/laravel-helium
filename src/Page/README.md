# Page module

Provider

```php
'providers' => [
    Dimsav\Translatable\TranslatableServiceProvider::class,
    Webup\LaravelHelium\Page\PageServiceProvider::class,
],
```

```php
// admin
Route::get('/pages', '\Webup\LaravelHelium\Page\Http\Controllers\Admin\PageController@index')->name('page.index');


// web
```

Menu

```php
<a href="{{ route('admin.page.index') }}" class="{{ current_class('admin.page', 'is-active') }}"><i class="fa fa-file icon"></i> Pages</a>
```
