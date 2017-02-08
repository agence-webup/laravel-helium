# Blog module

Dependencies

"yajra/laravel-datatables-oracle": "^7.0.1" : https://github.com/yajra/laravel-datatables

npm:
"datatables.net": "^1.10.13"
"datatables.net-dt": "^2.1.1"
"dropmic": "^0.1.5"
"froala-editor": "^2.4.1",


Provider

```php
'providers' => [
    Yajra\Datatables\DatatablesServiceProvider::class,
    Webup\LaravelHelium\Blog\BlogServiceProvider::class,
],
```

Publish migrations, views

```bash
$ php artisan vendor:publish --tag=helium.blog
```

Routes

```php
// admin
Route::get('/posts', '\Webup\LaravelHelium\Blog\Http\Controllers\Admin\PostController@index')->name('post.index');
Route::get('/posts/datatable', '\Webup\LaravelHelium\Blog\Http\Controllers\Admin\PostController@datatable')->name('post.datatable');
Route::get('/posts/create', '\Webup\LaravelHelium\Blog\Http\Controllers\Admin\PostController@create')->name('post.create');
Route::post('/posts', '\Webup\LaravelHelium\Blog\Http\Controllers\Admin\PostController@store')->name('post.store');
Route::get('/posts/{id}/edit', '\Webup\LaravelHelium\Blog\Http\Controllers\Admin\PostController@edit')->name('post.edit');
Route::put('/posts/{id}', '\Webup\LaravelHelium\Blog\Http\Controllers\Admin\PostController@update')->name('post.update');
Route::delete('/posts/{id}', '\Webup\LaravelHelium\Blog\Http\Controllers\Admin\PostController@destroy')->name('post.destroy');

Route::post('/images', '\Webup\LaravelHelium\Blog\Http\Controllers\Admin\ImageController@store')->name('image.store');

// web
Route::get('/blog', '\Webup\LaravelHelium\Blog\Http\Controllers\PostController@index')->name('blog.index');
Route::get('/blog/{slug}', '\Webup\LaravelHelium\Blog\Http\Controllers\PostController@show')->name('blog.show');
```

Menu

```php
<a href="{{ route('admin.post.index') }}" class="{{ current_class('admin.post', 'is-active') }}"><i class="fa fa-newspaper-o icon"></i> Blog</a>
```
