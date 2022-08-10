# laravel-helium


## Installation

Dependencies

- [helium](https://github.com/agence-webup/helium) `npm i -S helium-admin` or `npm i -S github:agence-webup/helium#v4`

Publish migrations, views and translations

```bash
$ php artisan vendor:publish --tag=helium
```

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
