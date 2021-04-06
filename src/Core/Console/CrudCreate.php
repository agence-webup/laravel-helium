<?php

namespace Webup\LaravelHelium\Core\Console;

use Illuminate\Console\Command;
use File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CrudCreate extends Command
{
    const WORD_GENDER_FEMININE = "Feminine";
    const WORD_GENDER_MASCULINE = "Masculine";


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'helium:crud {--force}';

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
    private $userFriendlyNameSingular = null;
    private $userFriendlyNamePlurial = null;

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

        if (count($models) < 1) {
            $this->error("No models found, please create model & migration before using helium CRUD generator");
            return;
        }

        //Get model instanciated from list model choice
        $this->modelName = $this->choice('Choose a model', array_keys($models));
        $this->selectedModel = $models[$this->modelName];

        //Test if table exist
        if (!Schema::hasTable($this->selectedModel->getTable())) {
            $this->error("No '" . $this->selectedModel->getTable() . "' table found, please create migration before using helium CRUD generator");
            return;
        }

        //Get model plural name (for generating views)
        $this->modelNamePlural = $this->selectedModel->getTable();

        //Get model singular name (for generating views)
        $this->modelNameSingular = Str::singular($this->modelNamePlural);

        $this->formatProperties();

        $this->userFriendlyNameSingular = strtolower($this->ask("Name displayed in views (singular)"));
        $this->userFriendlyNamePlurial = strtolower($this->ask("Name displayed in views (plurial)", $this->userFriendlyNameSingular . 's'));
        $this->modelGender = $this->choice(
            'What is the model gender?',
            [self::WORD_GENDER_FEMININE, self::WORD_GENDER_MASCULINE],
        );

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

        $generatedMenu = $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/config/menu.stub'));

        $heliumConfigPath = config_path('helium.php');
        $heliumConfigFile = file_get_contents($heliumConfigPath);
        if (strpos($heliumConfigFile, '// {{ Helium Crud Menu }}') === false) {
            $this->error("File " . $heliumConfigPath . " doesn't have `// {{ Helium Crud Menu }}` string which help Helium Crud Generator working");
            $this->comment("Please manually add following menu :");
            $this->info($generatedMenu);
        } else {
            $this->comment("Updating route `" . $heliumConfigPath . "`");
            $heliumConfigFile = str_replace('// {{ Helium Crud Menu }}', $generatedMenu, $heliumConfigFile);
            file_put_contents($heliumConfigPath, $heliumConfigFile);
        }
        $this->comment("");
    }

    protected function formatProperties()
    {
        $exeptedProperties = ["id", "created_at", "updated_at"];

        $dbProperties = $this->selectedModel->getConnection()->select("select * from INFORMATION_SCHEMA.COLUMNS where table_name = '" . $this->modelNamePlural . "'");
        // COLUMN_NAME
        // IS_NULLABLE
        // DATA_TYPE

        foreach ($dbProperties as $key => $dbProperty) {
            $temp = (array)$dbProperty;
            $dbProperty = array_combine(array_map('strtolower', array_keys($temp)), $temp);

            if (in_array($dbProperty["column_name"], $exeptedProperties)) {
                continue;
            }
            $this->modelProperties[] = [
                "name" => $dbProperty["column_name"],
                "required" => $dbProperty["is_nullable"] == "YES" ? false : true,
                "type" => $this->parseDbTypeToHtmlInputType($dbProperty["data_type"])
            ];
        }
    }

    private function parseDbTypeToHtmlInputType($dbType)
    {
        if (strpos($dbType, "int(") !== false) {
            return "number";
        }
        if (strpos($dbType, "varchar(") !== false) {
            return "text";
        }

        return "text";
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
        $newModels = $this->getModelsListFromFolder("Models");
        $oldModels = $this->getModelsListFromFolder("Entities");
        if (count($oldModels) > 0) {
            $this->warn(count($oldModels) . " models are using old 'Entities‘ folder stucture.");
            $this->warn("Please move them into 'Models' folder.");
        }

        return array_merge($newModels, $oldModels);
    }

    private function getModelsListFromFolder($folder)
    {
        $result = [];
        try {
            foreach (File::allFiles(app_path($folder)) as $key => $entity) {
                $classname = str_replace("/srv/http/app/" . $folder . "/", "", $entity->getRealPath());
                $classname = str_replace("/", "\\", $classname);
                $classname = str_replace(".php", "", $classname);
                $classnameWithNamespace = "\App\\" . $folder . "\\" . $classname;
                try {
                    $class = new $classnameWithNamespace();
                    if (is_subclass_of($class, "Illuminate\Database\Eloquent\Model")) {
                        $result[$classname] = new $class();
                    }
                } catch (\Throwable $e) {
                } catch (\Exception $e) {
                }
            }
        } catch (\Throwable $th) {
        }
        return $result;
    }


    private function createFile($destinationPath, $content, $confirm = false)
    {
        if (file_exists($destinationPath) && $confirm !== false && !$this->option('force')) {
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
            '{{ modelplural }}' => $this->modelNamePlural,
            '{{ userFriendlyNameSingular }}' => $this->userFriendlyNameSingular,
            '{{ userFriendlyNameSingularUcfirst }}' => ucfirst($this->userFriendlyNameSingular),
            '{{ userFriendlyNamePlurial }}' => $this->userFriendlyNamePlurial,
            '{{ userFriendlyNamePlurialUcfirst }}' => ucfirst($this->userFriendlyNamePlurial),
            '{{ genderPrefix }}' => $this->modelGender == self::WORD_GENDER_FEMININE ? "e" : "",
            '{{ modelGender }}' => $this->modelGender == self::WORD_GENDER_FEMININE ? "une" : "un",
            '{{ modelGenderDeterministic }}' => in_array(substr($this->userFriendlyNameSingular, 0, 1), ["a", "e", "i", "o", "u", "y"]) ? "l'" : ($this->modelGender == self::WORD_GENDER_FEMININE ? "la " : "le "),
            'de le' => "du",
        ];

        $extendedReplacers = [
            '{{ IndexDataSetLink }}' => ($this->needUpdate) ? 'row.dataset.link = "{{ route("admin.{{ modelsingular }}.edit", ["id" => "%id%"]) }}".replace("%id%",data.id);' : "",
            '{{ AddBtn }}' => ($this->needCreate) ? file_get_contents(__DIR__ . '/stubs/crud/views/elements/addBtn.stub') : "",
            '{{ SaveBtn }}' => ($this->needCreate) ? file_get_contents(__DIR__ . '/stubs/crud/views/elements/saveBtn.stub') : "",
            '{{ UpdateBtn }}' => ($this->needUpdate) ? file_get_contents(__DIR__ . '/stubs/crud/views/elements/updateBtn.stub') : "",
            '{{ DatatableEditBtn }}' => ($this->needUpdate) ? file_get_contents(__DIR__ . '/stubs/crud/views/elements/datatable-actions-edit.stub') : "",
            '{{ DatatableDeleteBtn }}' => ($this->needDelete) ? file_get_contents(__DIR__ . '/stubs/crud/views/elements/datatable-actions-delete.stub') : "",
            '{{ ReadRoutes }}' => ($this->needRead) ? file_get_contents(__DIR__ . '/stubs/crud/routes/read.stub') : "",
            '{{ CreateRoutes }}' => ($this->needCreate) ? file_get_contents(__DIR__ . '/stubs/crud/routes/create.stub') : "",
            '{{ UpdateRoutes }}' => ($this->needUpdate) ? file_get_contents(__DIR__ . '/stubs/crud/routes/update.stub') : "",
            '{{ DeleteRoutes }}' => ($this->needDelete) ? file_get_contents(__DIR__ . '/stubs/crud/routes/delete.stub') : "",
            '{{ RequestModelProperties }}' => $this->createRequestModelProperties(),
            '{{ JobModelPropertiesSetters }}' => $this->createJobModelPropertiesSetters(),
            '{{-- Helium Crud Form --}}' => $this->replaceInFormStub(),
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
