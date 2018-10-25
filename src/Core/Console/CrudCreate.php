<?php

namespace Webup\LaravelHelium\Core\Console;

use Illuminate\Console\Command;
use Webup\LaravelHelium\Core\Entities\AdminUser;
use File;
use Illuminate\Support\Str;
use DB;

class CrudCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'helium:crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create crud for helium admin';

    private $selectedModel = null;
    private $modelName = null;
    private $modelNamePlural = null;
    private $modelNameSingular = null;
    private $modelProperties = [];

    private $viewDirectory = null;
    private $viewElementDirectory = null;
    private $viewFormDirectory = null;
    private $controllerDirectory = null;
    private $jobDirectory = null;
    private $requestDirectory = null;
    private $repositoryDirectory = null;


    private $needCreate = null;
    private $needUpdate = null;
    private $needRead = null;
    private $needDelete = null;


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Create helium CRUD");

        $models = $this->getModelsList();

        //Get model instanciated from list model choice
        $this->modelName = $this->choice('Choose a model', array_keys($models));
        $this->selectedModel = $models[$this->modelName];

        //Get model plural name (for generating views)
        $this->modelNamePlural = $this->selectedModel->getTable();
        //Get model singular name (for generating views)
        $this->modelNameSingular = Str::singular($this->modelNamePlural);

        $this->formatProperties();

        //Ask for C.R.U.D
        $this->needCreate = $this->confirm("Need Create controller & views ?", true);
        $this->needUpdate = $this->confirm("Need Update controller & views ?", true);
        $this->needRead = $this->confirm("Need Index controller & views ?", true);
        $this->needDelete = $this->confirm("Need Delete controller ?", true);

        $this->createDirectories();

        $this->processAnswers();

        $this->info("Helium CRUD was created");
    }

    protected function createDirectories()
    {
        $directories = [
            "viewDirectory" => resource_path('views/admin/' . $this->modelNameSingular . '/'),
            "viewElementDirectory" => resource_path('views/admin/' . $this->modelNameSingular . '/elements/'),
            "viewFormDirectory" => resource_path('views/admin/' . $this->modelNameSingular . '/form/'),
            "controllerDirectory" => app_path('Http/Controllers/Admin/' . $this->modelName . '/'),
            "jobDirectory" => app_path('Jobs/' . $this->modelName . '/'),
            "requestDirectory" => app_path('Http/Requests/Admin/' . $this->modelName . '/'),
            "repositoryDirectory" => app_path('Repositories/'),
        ];

        $this->info("Step : Directories");
        foreach ($directories as $key => $directory) {
            $this->{$key} = $directory;
            if (!is_dir($this->{$key})) {
                $this->comment("Creating `" . $this->{$key} . "` directory");
                mkdir($this->{$key}, 0755, true);
            }
        }
        $this->comment("");
    }


    protected function processAnswers()
    {
        foreach ($this->parseAnswers() as $key => $data) {
            //Check if user want C/R/U/D.
            if (array_get($data, "need", false)) {
                $this->info("Step : " . ucfirst($key));
                //Check if choosen C/R/U/D need create view
                if ($viewKey = array_get($data, "view", null)) {
                    $destinationPath = $this->viewDirectory . array_get($viewKey, "template");
                    $this->comment("Creating view `" . $destinationPath . "`");
                    $this->createFile(
                        $destinationPath,
                        $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/views/' . array_get($viewKey, "stub"))),
                        "The [{$destinationPath}] view element already exists. Do you want to replace it?"
                    );
                }
                //Check if choosen C/R/U/D need create controller
                if ($controllerKey = array_get($data, "controller", null)) {
                    $destinationPath = $this->controllerDirectory . array_get($controllerKey, "template");
                    $this->comment("Creating controller `" . $destinationPath . "`");
                    $this->createFile(
                        $destinationPath,
                        $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/controllers/' . array_get($controllerKey, "stub"))),
                        "The [{$destinationPath}] controller element already exists. Do you want to replace it?"
                    );
                }
                //Check if choosen C/R/U/D need create job
                if ($jobKey = array_get($data, "job", null)) {
                    $destinationPath = $this->jobDirectory . str_replace("Job", $this->modelName, array_get($jobKey, "template"));
                    $this->comment("Creating job `" . $destinationPath . "`");
                    $this->createFile(
                        $destinationPath,
                        $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/jobs/' . array_get($jobKey, "stub"))),
                        "The [{$destinationPath}] job element already exists. Do you want to replace it?"
                    );
                }
                //Check if choosen C/R/U/D need create request
                if ($requestKey = array_get($data, "request", null)) {
                    $destinationPath = $this->requestDirectory . array_get($requestKey, "template");
                    $this->comment("Creating request `" . $destinationPath . "`");
                    $this->createFile(
                        $destinationPath,
                        $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/requests/' . array_get($requestKey, "stub"))),
                        "The [{$destinationPath}] request element already exists. Do you want to replace it?"
                    );
                }
                $this->comment("");
            }
        }
        $this->processForm();
        $this->processRepository();
        $this->processDatatableActions();
        $this->processRoutes();
        $this->processMenu();
    }

    private function processForm()
    {
        $this->info("Step : Form");
        if ($this->needCreate || $this->needUpdate) {
            foreach (['form.stub' => 'form.blade.php', 'javascript.stub' => 'javascript.blade.php'] as $key => $value) {
                $generatedView = $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/views/form/' . $key));
                $destinationPath = $this->viewFormDirectory . $value;
                $this->comment("Creating view element `" . $destinationPath . "`");
                $this->createFile($destinationPath, $generatedView, "The [{$destinationPath}] view form already exists. Do you want to replace it?");
            }
        }
        $this->comment("");

    }

    private function processRepository()
    {
        $this->info("Step : Repository");
        if ($this->needCreate || $this->needRead || $this->needUpdate || $this->needDelete) {
            $generatedView = $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/repositories/ModelRepository.stub'));
            $destinationPath = $this->repositoryDirectory . str_replace("Model", $this->modelName, "ModelRepository.php");
            $this->comment("Creating repository `" . $destinationPath . "`");
            $this->createFile($destinationPath, $generatedView, "The [{$destinationPath}] repository already exists. Do you want to replace it?");
        }
        $this->comment("");
    }

    private function processDatatableActions()
    {
        $this->info("Step : Datatable action");
        if ($this->needRead) {
            $generatedView = $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/views/elements/datatable-actions.stub'));
            $destinationPath = $this->viewElementDirectory . "datatable-actions.blade.php";
            $this->comment("Creating view `" . $destinationPath . "`");
            $this->createFile($destinationPath, $generatedView, "The [{$destinationPath}] view element already exists. Do you want to replace it?");
        }
        $this->comment("");
    }

    private function processRoutes()
    {
        $this->info("Step : Routes");

        $generatedRoutes = $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/routes/group.stub'));
        $adminRoutePath = base_path('routes/admin.php');
        $adminRouteFile = file_get_contents($adminRoutePath);
        if (strpos($adminRouteFile, '// {{ Helium Crud }}') === false) {
            $this->error("File " . $adminRoutePath . " doesn't have `// {{ Helium Crud }}` string which help Helium Crud Generator working");
            $this->comment("Please manually add following routes :");
            $this->info($generatedRoutes);
        } else {
            $this->comment("Updating route `" . $adminRoutePath . "`");
            $adminRouteFile = str_replace('// {{ Helium Crud }}', $generatedRoutes, $adminRouteFile);
            file_put_contents($adminRoutePath, $adminRouteFile);
        }
        $this->comment("");
    }

    private function processMenu()
    {
        $this->info("Step : Menu");
        $generatedMenu = $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/views/elements/menu.stub'));
        $menuRoutePath = resource_path('views/vendor/helium/elements/menu.blade.php');
        if (!file_exists($menuRoutePath)) {
            $this->error("Unable to find " . $menuRoutePath . " file. Did you run `php artisan vendor:publish --tag=helium` command ?");
            return;
        }
        $menuRouteFile = file_get_contents($menuRoutePath);
        if (strpos($menuRouteFile, '{{-- Helium Crud --}}') === false) {
            $this->error("File " . $menuRoutePath . " doesn't have `{{-- Helium Crud --}}` string which help Helium Crud Generator working");
            $this->comment("Please manually add following menu :");
            $this->info($generatedMenu);
        } else {
            $this->comment("Updating menu `" . $menuRoutePath . "`");
            $menuRouteFile = str_replace('{{-- Helium Crud --}}', $generatedMenu, $menuRouteFile);
            file_put_contents($menuRoutePath, $menuRouteFile);
        }
        $this->comment("");

    }

    protected function formatProperties()
    {
        $exeptedProperties = ["id", "created_at", "updated_at"];
        $dbProperties = DB::select("DESCRIBE $this->modelNamePlural");

        foreach ($dbProperties as $key => $dbProperty) {
            if (in_array($dbProperty->Field, $exeptedProperties)) {
                continue;
            }
            $this->modelProperties[] = [
                "name" => $dbProperty->Field,
                "required" => $dbProperty->Null == "YES" ? false : true,
                "type" => $this->parseDbTypeToHtmlInputType($dbProperty->Type)
            ];
        }
    }

    private function parseDbTypeToHtmlInputType($dbType)
    {
        switch (true) {
            case strpos($dbType, "int(") !== false:
                return "number";
            case strpos($dbType, "varchar(") !== false:
                return "text";
            default:
                return "text";
                break;
        }
    }

    protected function parseAnswers()
    {
        return [
            'create' => [
                "controller" => [
                    "stub" => "CreateController.stub",
                    "template" => "CreateController.php"
                ],
                "request" => [
                    "stub" => "Store.stub",
                    "template" => "Store.php"
                ],
                "job" => [
                    "stub" => "StoreJob.stub",
                    "template" => "StoreJob.php"
                ],
                "view" => [
                    "stub" => "create.stub",
                    "template" => "create.blade.php"
                ],
                "need" => $this->needCreate,
            ],
            'edit' => [
                "controller" => [
                    "stub" => "EditController.stub",
                    "template" => "EditController.php"
                ],
                "request" => [
                    "stub" => "Update.stub",
                    "template" => "Update.php"
                ],
                "job" => [
                    "stub" => "UpdateJob.stub",
                    "template" => "UpdateJob.php"
                ],
                "view" => [
                    "stub" => "edit.stub",
                    "template" => "edit.blade.php"
                ],
                "need" => $this->needUpdate,
            ],
            'delete' => [
                "controller" => [
                    "stub" => "DestroyController.stub",
                    "template" => "DestroyController.php"
                ],
                "request" => [
                    "stub" => "Destroy.stub",
                    "template" => "Destroy.php"
                ],
                "job" => [
                    "stub" => "DestroyJob.stub",
                    "template" => "DestroyJob.php"
                ],
                "need" => $this->needDelete,
            ],
            'index' => [
                "controller" => [
                    "stub" => "IndexController.stub",
                    "template" => "IndexController.php"
                ],
                "view" => [
                    "stub" => "index.stub",
                    "template" => "index.blade.php"
                ],
                "need" => $this->needRead,
            ],
        ];
    }

    private function getModelsList()
    {
        $result = [];
        foreach (File::allFiles(app_path("Entities")) as $key => $entity) {
            $classname = str_replace(".php", "", $entity->getFilename());
            $classnameWithNamespace = "\App\Entities\\" . $classname;
            try {
                $result[$classname] = new $classnameWithNamespace();
            } catch (\Exception $e) {

            }
        }
        return $result;
    }



    private function createFile($destinationPath, $content, $confirm = false)
    {
        if (file_exists($destinationPath) && $confirm !== false) {
            if (!$this->confirm($confirm)) {
                return;
            }
        }
        file_put_contents($destinationPath, $content);
    }

    protected function compileStub($path)
    {
        return $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/' . $path));
    }

    private function replaceInFormStub()
    {
        $result = "";

        foreach ($this->modelProperties as $modelProperty) {
            $stubElementContent = file_get_contents(__DIR__ . '/stubs/crud/views/form/elements/' . $modelProperty['type'] . '.stub');

            $stubReplacers = [
                '{{ property }}' => $modelProperty['name'],
                '{{ type }}' => $modelProperty['type'],
                '{{ required }}' => $modelProperty['required'] ? "->required()" : "",
            ];

            foreach ($stubReplacers as $key => $value) {
                $stubElementContent = str_replace($key, $value, $stubElementContent);
            }

            $result .= $stubElementContent;
        }

        return $result;
    }

    protected function createRequestModelProperties()
    {
        $result = [];
        $temp = [];
        foreach ($this->modelProperties as $key => $modelProperty) {
            $temp[array_get($modelProperty, 'name')] = array_get($modelProperty, 'required', false) ? "required" : "";
        }
        foreach ($temp as $key => $t) {
            $result[] = '"' . $key . '" => "' . $t . '"';
        }
        return implode("," . PHP_EOL, $result);
    }

    protected function createJobModelPropertiesSetters()
    {
        $result = "";
        foreach ($this->modelProperties as $key => $modelProperty) {
            $result .= '$' . $this->modelNameSingular . '->' . array_get($modelProperty, "name") . ' = array_get($this->data,"' . array_get($modelProperty, "name") . '");' . PHP_EOL;
        }
        return $result;
    }

    protected function replaceInStub($stubContent)
    {
        $stubReplacers = [
            '{{ Model }}' => $this->modelName,
            '{{ modelsingular }}' => $this->modelNameSingular,
            '{{ modelplural }}' => $this->modelNamePlural
        ];

        $extendedReplacers = [
            '{{ IndexDataSetLink }}' => ($this->needUpdate) ? 'row.dataset.link = "{{ route("admin.{{ modelsingular }}.edit", ["id" => "%id%"]) }}".replace("%id%",data.id);' : "",
            '{{ AddBtn }}' => ($this->needCreate) ? file_get_contents(__DIR__ . '/stubs/crud/views/elements/addBtn.stub') : "",
            '{{ DatatableEditBtn }}' => ($this->needUpdate) ? file_get_contents(__DIR__ . '/stubs/crud/views/elements/datatable-actions-edit.stub') : "",
            '{{ DatatableDeleteBtn }}' => ($this->needDelete) ? file_get_contents(__DIR__ . '/stubs/crud/views/elements/datatable-actions-delete.stub') : "",
            '{{ ReadRoutes }}' => ($this->needRead) ? file_get_contents(__DIR__ . '/stubs/crud/routes/read.stub') : "",
            '{{ CreateRoutes }}' => ($this->needCreate) ? file_get_contents(__DIR__ . '/stubs/crud/routes/create.stub') : "",
            '{{ UpdateRoutes }}' => ($this->needUpdate) ? file_get_contents(__DIR__ . '/stubs/crud/routes/update.stub') : "",
            '{{ DeleteRoutes }}' => ($this->needDelete) ? file_get_contents(__DIR__ . '/stubs/crud/routes/delete.stub') : "",
            '{{ RequestModelProperties }}' => $this->createRequestModelProperties(),
            '{{ JobModelPropertiesSetters }}' => $this->createJobModelPropertiesSetters(),
            '{{-- Helium Crud Form --}}' => $this->replaceInFormStub()
        ];

        foreach ($extendedReplacers as $searchString => $replaceBy) {
            if (strpos($stubContent, $searchString) !== false) {
                $stubContent = str_replace($searchString, $this->replaceInStub($replaceBy), $stubContent);
            }
        }

        foreach ($stubReplacers as $searchString => $replaceBy) {
            $stubContent = str_replace($searchString, $replaceBy, $stubContent);
        }

        return $stubContent;
    }
}
