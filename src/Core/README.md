# laravel-helium


## Installation

Dependencies

- [helium](https://github.com/agence-webup/helium) `npm i -S helium-admin` or `npm i -S github:agence-webup/helium#v4`

Publish migrations, views and translations

```bash
$ php artisan vendor:publish --tag=helium
```

`webup/laravel-form` is registered by Helium itself (provider + `Form` alias),
so there is nothing to add to `config/app.php` — which Laravel 11 removed anyway.

## Upgrading an existing project to Laravel 11+

Two published files are not updated by `composer update`, so merge them by hand:

- `routes/admin.php` — the two `/login` routes now carry `->middleware('admin.guest:admin')`.
  Laravel 11 removed `Controller::middleware()`, so the guest redirect is declared on
  the routes instead of in `AuthController`'s constructor.
- `resources/lang/vendor/helium` — Laravel moved the lang directory to the project
  root in Laravel 9. Move your translation overrides to `lang/vendor/helium`, then
  delete `resources/lang` if it is otherwise empty (as long as that directory exists,
  Laravel uses it as the lang path for the whole application).

# Redirections
    protected $middleware = [
        [...]
        \Webup\LaravelHelium\Redirection\Http\Middleware\RedirectOldUrls::class
    ];

# Crud Generator

## How to use
 
**⚠️ Important ⚠️**

You need create migration & entity : Helium crud generator use class in entities folder ( `{your_project_path}/app/Entities`) for listing available crud and migration to create form.


Then, you can run

```bash
$ php artisan helium:crud
```
You can add `--force` argument to auto-replace file if already exists (except Repository).

### After creating crud

You need manually to update following file: 

- Menu (`{your_project_path}/resources/views/vendor/helium/elements/menu.blade.php`): 
    - Menu icon
    - Menu label

- Index (`{your_project_path}/resources/views/admin/{your_model_name}/index.blade.php`): 
    - Title 
    - Add button label 
    - Datatable collumns 
        - View 
        - Javascript 
        - Controller : update select request in `datatable` function (`{your_project_path}/app/Http/Controllers/Admin/{your_model_name}/IndexController.php`)

- Create (`{your_project_path}/resources/views/admin/{your_model_name}/create.blade.php`): 
    - Title 
    - Create button label 

 - Edit (`{your_project_path}/resources/views/admin/{your_model_name}/edit.blade.php`): 
    - Title 
    - Edit button label 

 - Form (`{your_project_path}/resources/views/admin/{your_model_name}/form/form.blade.php`): 
    - Customize (fields, box, validation, ...)
