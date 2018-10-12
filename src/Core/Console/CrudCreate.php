<?php

namespace Webup\LaravelHelium\Core\Console;

use Illuminate\Console\Command;
use Webup\LaravelHelium\Core\Entities\AdminUser;
use File;
use Illuminate\Support\Str;

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

    private $viewDirectory = null;
    private $viewElementDirectory = null;
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

        //Ask for C.R.U.D
        $this->needCreate = $this->confirm("Need Create controller & views ?");
        $this->needUpdate = $this->confirm("Need Update controller & views ?");
        $this->needRead = $this->confirm("Need Index controller & views ?");
        $this->needDelete = $this->confirm("Need Delete controller ?");

        $this->createDirectories();

        $this->processAnswers();

        $this->info("Helium CRUD was created");
    }

    protected function processAnswers()
    {

        foreach ($this->parseAnswers() as $key => $data) {
            //Check if user want C/R/U/D.
            if (array_get($data, "need", false)) {

                $this->comment("Step : " . ucfirst($key));
                //Check if choosen C/R/U/D need create view
                if ($viewKey = array_get($data, "view", null)) {
                    $destinationPath = $this->viewDirectory . array_get($viewKey, "template");
                    $this->comment("Creating view `" . $destinationPath . "`");
                    $stubPath = 'views/' . array_get($viewKey, "stub");
                    if (file_exists($destinationPath)) {
                        if ($this->confirm("The [{$destinationPath}] view already exists. Do you want to replace it?")) {
                            $this->createFile($stubPath, $destinationPath);
                        }
                    } else {
                        $this->createFile($stubPath, $destinationPath);
                    }
                }
                //Check if choosen C/R/U/D need create controller
                if ($controllerKey = array_get($data, "controller", null)) {
                    $destinationPath = $this->controllerDirectory . array_get($controllerKey, "template");
                    $this->comment("Creating controller `" . $destinationPath . "`");
                    $stubPath = 'controllers/' . array_get($controllerKey, "stub");
                    if (file_exists($destinationPath)) {
                        if ($this->confirm("The [{$destinationPath}] controller already exists. Do you want to replace it?")) {
                            $this->createFile($stubPath, $destinationPath);
                        }
                    } else {
                        $this->createFile($stubPath, $destinationPath);
                    }
                }
                //Check if choosen C/R/U/D need create job
                if ($jobKey = array_get($data, "job", null)) {
                    $destinationPath = $this->jobDirectory . str_replace("Job", $this->modelName, array_get($jobKey, "template"));
                    $this->comment("Creating job `" . $destinationPath . "`");
                    $stubPath = 'jobs/' . array_get($jobKey, "stub");
                    if (file_exists($destinationPath)) {
                        if ($this->confirm("The [{$destinationPath}] job already exists. Do you want to replace it?")) {
                            $this->createFile($stubPath, $destinationPath);
                        }
                    } else {
                        $this->createFile($stubPath, $destinationPath);
                    }
                }
                //Check if choosen C/R/U/D need create request
                if ($requestKey = array_get($data, "request", null)) {
                    $destinationPath = $this->requestDirectory . array_get($requestKey, "template");
                    $this->comment("Creating request `" . $destinationPath . "`");
                    $stubPath = 'requests/' . array_get($requestKey, "stub");
                    if (file_exists($destinationPath)) {
                        if ($this->confirm("The [{$destinationPath}] request already exists. Do you want to replace it?")) {
                            $this->createFile($stubPath, $destinationPath);
                        }
                    } else {
                        $this->createFile($stubPath, $destinationPath);
                    }
                }
                $this->comment("");
            }
        }

        $this->processRoutes();

        $this->processMenu();

        $this->processDatatableActions();
    }

    private function processDatatableActions()
    {
        //If user want read and (update or delete)
        if ($this->needRead && ($this->needUpdate || $this->needDelete)) {
            $stubContent = file_get_contents(__DIR__ . '/stubs/crud/views/elements/datatable-actions.stub');

            $stubContent = str_replace('{{ EditBtn }}', $this->needUpdate ? file_get_contents(__DIR__ . '/stubs/crud/views/elements/datatable-actions-edit.stub') : "", $stubContent);
            $stubContent = str_replace('{{ DeleteBtn }}', $this->needDelete ? file_get_contents(__DIR__ . '/stubs/crud/views/elements/datatable-actions-delete.stub') : "", $stubContent);


            $generatedView = $this->replaceInStub($stubContent);

            $destinationPath = $this->viewElementDirectory . "datatable-actions.blade.php";

            if (file_exists($destinationPath)) {
                if ($this->confirm("The [{$destinationPath}] view element already exists. Do you want to replace it?")) {
                    file_put_contents($destinationPath, $generatedView);
                }
            } else {
                file_put_contents($destinationPath, $generatedView);
            }

        }
    }

    private function processRoutes()
    {
        $stubContent = file_get_contents(__DIR__ . '/stubs/crud/routes/group.stub');

        $stubContent = str_replace('{{ ReadRoutes }}', $this->needRead ? file_get_contents(__DIR__ . '/stubs/crud/routes/read.stub') : "", $stubContent);
        $stubContent = str_replace('{{ CreateRoutes }}', $this->needCreate ? file_get_contents(__DIR__ . '/stubs/crud/routes/create.stub') : "", $stubContent);
        $stubContent = str_replace('{{ UpdateRoutes }}', $this->needUpdate ? file_get_contents(__DIR__ . '/stubs/crud/routes/update.stub') : "", $stubContent);
        $stubContent = str_replace('{{ DeleteRoutes }}', $this->needDelete ? file_get_contents(__DIR__ . '/stubs/crud/routes/delete.stub') : "", $stubContent);

        $generatedRoutes = $this->replaceInStub($stubContent);

        $adminRoutePath = base_path('routes/admin.php');
        $adminRouteFile = file_get_contents($adminRoutePath);

        if (strpos($adminRouteFile, '// {{ Helium Crud }}') === false) {
            $this->error("File " . $adminRoutePath . " doesn't have `// {{ Helium Crud }}` string which help Helium Crud Generator working");
            $this->comment("Please manually add following routes :");
            $this->info($generatedRoutes);
        } else {
            $adminRouteFile = str_replace('// {{ Helium Crud }}', $generatedRoutes, $adminRouteFile);
            file_put_contents($adminRoutePath, $adminRouteFile);
        }
    }

    private function processMenu()
    {
        $stubContent = file_get_contents(__DIR__ . '/stubs/crud/views/elements/menu.stub');

        $generatedMenu = $this->replaceInStub($stubContent);

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
            $menuRouteFile = str_replace('{{-- Helium Crud --}}', $generatedMenu, $menuRouteFile);
            file_put_contents($menuRoutePath, $menuRouteFile);
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

    /**
     * Create the directories for the files.
     *
     * @return void
     */
    protected function createDirectories()
    {
        //Create views directory
        $this->viewDirectory = resource_path('views/admin/' . $this->modelNameSingular . '/');
        if (!is_dir($this->viewDirectory)) {
            mkdir($this->viewDirectory, 0755, true);
        }
        //Create views element directory
        $this->viewElementDirectory = resource_path('views/admin/' . $this->modelNameSingular . '/elements/');
        if (!is_dir($this->viewElementDirectory)) {
            mkdir($this->viewElementDirectory, 0755, true);
        }
        //Create controllers directory
        $this->controllerDirectory = app_path('Http/Controllers/Admin/' . $this->modelName . '/');
        if (!is_dir($this->controllerDirectory)) {
            mkdir($this->controllerDirectory, 0755, true);
        }
        //Create jobs directory
        $this->jobDirectory = app_path('Jobs/' . $this->modelName . '/');
        if (!is_dir($this->jobDirectory)) {
            mkdir($this->jobDirectory, 0755, true);
        }
        //Create requests directory
        $this->requestDirectory = app_path('Http/Requests/Admin/' . $this->modelName . '/');
        if (!is_dir($this->requestDirectory)) {
            mkdir($this->requestDirectory, 0755, true);
        }
        //Create repositories directory
        $this->repositoryDirectory = app_path('Repositories/');
        if (!is_dir($this->repositoryDirectory)) {
            mkdir($this->repositoryDirectory, 0755, true);
        }
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



    private function createFile($stubPath, $path)
    {
        $compiledFile = $this->compileStub($stubPath, $path);
        file_put_contents($path, $compiledFile);
    }

    protected function compileStub($path)
    {
        return $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/' . $path));
    }

    protected function replaceInStub($stubContent)
    {
        $stubContent = str_replace('{{ Model }}', $this->modelName, $stubContent);
        $stubContent = str_replace('{{ modelsingular }}', $this->modelNameSingular, $stubContent);
        $stubContent = str_replace('{{ modelplural }}', $this->modelNamePlural, $stubContent);

        return $stubContent;
    }
}
