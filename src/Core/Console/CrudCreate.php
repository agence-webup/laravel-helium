<?php

namespace Webup\LaravelHelium\Core\Console;

use Exception;
use Illuminate\Console\Command;
use File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Webup\LaravelHelium\Core\Console\helpers\FeatherIcons;

class CrudCreate extends Command
{
    const WORD_GENDER_FEMININE = "Feminin";
    const WORD_GENDER_MASCULINE = "Masculin";
    const WORD_GENDER_NO = "Non genré";


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

    private $modelFormProperties = [];
    private $modelDataTableProperties = [];
    private $menuIcon = null;
    private $permissionsString = null;


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
        $this->info("CRUD generator d'helium");

        $models = $this->getModelsList();

        if (count($models) < 1) {
            $this->error("Aucun model trouvé, veuillez créer le model et la migration avant d'utiliser le crud generator");
            return;
        }

        //Get model instanciated from list model choice
        $this->modelName = $this->choice('Choisissez le model', array_keys($models));
        $this->selectedModel = $models[$this->modelName];
        //Test if table exist
        if (!Schema::connection($this->selectedModel->getConnectionName())->hasTable($this->selectedModel->getTable())) {
            $this->error("Aucune table '" . $this->selectedModel->getTable() . "' trouvée, veuillez créer la migration et l'executer avant d'utiliser le crud generator");
            return;
        }
        $this->formatProperties();
        //Get model plural name (for generating views)
        $this->modelNamePlural = $this->ask("Nom du model au pluriel utilisé pour les permissions, variables, ... (habituellement le nom de la table)", $this->selectedModel->getTable());

        //Get model singular name (for generating views)
        $this->modelNameSingular = $this->ask("Nom du model au singulier utilisé pour les routes, variables, ... ", Str::singular($this->modelNamePlural));

        $this->userFriendlyNameSingular = strtolower($this->ask("Nom utilisé pour les vues (singulier)"));
        $this->userFriendlyNamePlurial = strtolower($this->ask("Nom utilisé pour le vues (pluriel)", $this->userFriendlyNameSingular . 's'));
        $this->modelGender = $this->choice(
            "Quel est le genre du mot '" . $this->userFriendlyNameSingular . "' ?",
            [self::WORD_GENDER_FEMININE, self::WORD_GENDER_MASCULINE, self::WORD_GENDER_NO],
        );

        //Ask for C.R.U.D
        $this->needCreate = $this->confirm("Voulez-vous créer les controlleurs & vues pour la Création ?", true);
        $this->needUpdate = $this->confirm("Voulez-vous créer les controlleurs & vues pour la Mise à jour ?", true);
        $this->needRead = $this->confirm("Voulez-vous créer les controlleurs & vues pour la Lecture ?", true);
        $this->needDelete = $this->confirm("Voulez-vous créer le controlleur pour la Suppression ?", true);

        $this->createDirectories();

        $this->processAnswers();
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

        $this->info("Étape : Dossiers");
        foreach ($directories as $key => $directory) {
            $this->{$key} = $directory;
            if (!is_dir($this->{$key})) {
                $this->comment("Création du dossier `" . $this->{$key} . "`");
                mkdir($this->{$key}, 0755, true);
            }
        }
        $this->comment("");
    }


    protected function processAnswers()
    {
        $this->processModelPropertiesFilter();

        $this->processPermissions();

        foreach ($this->parseAnswers() as $key => $data) {
            //Check if user want C/R/U/D.
            if (array_get($data, "need", false)) {
                $this->info("Étape : " . ucfirst($key));
                //Check if choosen C/R/U/D need create view
                if ($viewKey = array_get($data, "view", null)) {
                    $destinationPath = $this->viewDirectory . array_get($viewKey, "template");
                    $this->comment("Création de la vue `" . $destinationPath . "`");
                    $this->createFile(
                        $destinationPath,
                        $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/views/' . array_get($viewKey, "stub"))),
                        "La vue [{$destinationPath}] existe déjà. Voulez-vous la remplacer ?"
                    );
                }
                //Check if choosen C/R/U/D need create controller
                if ($controllerKey = array_get($data, "controller", null)) {
                    $destinationPath = $this->controllerDirectory . array_get($controllerKey, "template");
                    $this->comment("Création du controlleur `" . $destinationPath . "`");
                    $this->createFile(
                        $destinationPath,
                        $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/controllers/' . array_get($controllerKey, "stub"))),
                        "Le controlleur [{$destinationPath}] existe déjà. Voulez-vous le remplacer ?"
                    );
                }
                //Check if choosen C/R/U/D need create job
                if ($jobKey = array_get($data, "job", null)) {
                    $destinationPath = $this->jobDirectory . str_replace("Job", $this->modelName, array_get($jobKey, "template"));
                    $this->comment("Création du job `" . $destinationPath . "`");
                    $this->createFile(
                        $destinationPath,
                        $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/jobs/' . array_get($jobKey, "stub"))),
                        "Le job [{$destinationPath}] existe déjà. Voulez-vous le remplacer ?"
                    );
                }
                //Check if choosen C/R/U/D need create request
                if ($requestKey = array_get($data, "request", null)) {
                    $destinationPath = $this->requestDirectory . array_get($requestKey, "template");
                    $this->comment("Création de la requête `" . $destinationPath . "`");
                    $this->createFile(
                        $destinationPath,
                        $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/requests/' . array_get($requestKey, "stub"))),
                        "La requête [{$destinationPath}] existe déjà. Voulez-vous la remplacer ?"
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

        if ($this->permissionsString != null) {
            if ($this->confirm("Les permissions nécessite de lancer la commande `php artisan migrate`. Executer maintenant ?", true)) {
                $this->call("migrate");
            } else {
                $this->comment("Veuillez executer la commande `php artisan migrate`");
            }
        }
    }

    private function processModelPropertiesFilter()
    {
        $propertyList = [];
        $selectedIndex = [];
        foreach ($this->modelProperties as $key => $modelProperty) {
            $propertyList[] = $modelProperty["name"];
            if ($modelProperty["required"]) {
                $selectedIndex[] = count($propertyList) - 1;
            }
        }


        if ($this->needCreate || $this->needUpdate) {
            $this->modelFormProperties = $this->choice(
                'Quels sont les champs à utiliser pour le/les formulaire(s) ?',
                $propertyList,
                implode(",", $selectedIndex),
                null,
                true
            );
        }

        if ($this->needRead) {
            $this->modelDataTableProperties = $this->choice(
                'Quels sont les champs à afficher dans le DataTable ?',
                $propertyList,
                null,
                null,
                true
            );
        }
    }


    private function processPermissions()
    {
        $this->info("Étape : Permissions");

        $permissions = [];

        if ($this->needCreate) {
            $permissions[] = $this->preparePermission("create", $this->replaceInStub("Créer des {{ userFriendlyNamePlurial }}"));
        }
        if ($this->needUpdate) {
            $permissions[] = $this->preparePermission("update", $this->replaceInStub("Mettre à jour les {{ userFriendlyNamePlurial }}"));
        }
        if ($this->needDelete) {
            $permissions[] = $this->preparePermission("delete", $this->replaceInStub("Supprimer les {{ userFriendlyNamePlurial }}"));
        }
        if ($this->needRead) {
            $permissions[] = $this->preparePermission("read", $this->replaceInStub("Voir les {{ userFriendlyNamePlurial }}"));
        }
        do {
            $morePermission = $this->confirm("Voulez vous ajouter une permission ?");
            if (!$morePermission) {
                break;
            }
            $prefix = $this->modelNamePlural . ".";
            $permissionKey = Str::slug($this->ask("Identifiant de la permission (sera préfixé par '" . $prefix . "')"));
            $permissionKey = str_replace($prefix, "", $permissionKey);
            $permissions[] = $this->preparePermission($permissionKey, "");
        } while ($morePermission);

        if (count($permissions) > 0) {
            $this->comment("Création de " . count($permissions) . " permission(s)");
            $this->permissionsString = "";
            foreach ($permissions as $key => $permissionData) {
                $stubElementContent = file_get_contents(__DIR__ . '/stubs/crud/permissions/Permission.stub');
                $stubReplacers = [
                    '{{ permission_name }}' => $permissionData['name'],
                    '{{ permission_guard_name }}' => $permissionData['guard_name'],
                    '{{ permission_title }}' => $permissionData['title'],
                ];
                foreach ($stubReplacers as $key => $value) {
                    $stubElementContent = str_replace($key, $value, $stubElementContent);
                }
                $this->permissionsString .= $stubElementContent;
            }
            $destinationPath = database_path('/migrations/' . date('Y_m_d_His') . "_create_" . $this->modelNameSingular . "_permissions.php");

            $this->createFile(
                $destinationPath,
                $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/database/permissions.stub')),
            );
        }
    }

    private function preparePermission($key, $title)
    {
        $name = $this->modelNamePlural . "." . $key;

        try {
            $permission = Permission::findByName($name, "admin");
            $this->warn("La permission `" . $name . "` existe déjà: seule la description sera mise à jour.");
            $title = $permission->title;
        } catch (\Throwable $th) {
        }

        $title = $this->ask("Descriptif de la permission `" . $name . "`", $title);
        return [
            'name' => $name,
            'guard_name' => 'admin',
            'title' => $title
        ];
    }



    private function processForm()
    {
        $this->info("Étape : Formulaires");
        if ($this->needCreate || $this->needUpdate) {
            foreach (['form.stub' => 'form.blade.php', 'javascript.stub' => 'javascript.blade.php'] as $key => $value) {
                $generatedView = $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/views/form/' . $key));
                $destinationPath = $this->viewFormDirectory . $value;
                $this->comment("Création de la vue `" . $destinationPath . "`");
                $this->createFile($destinationPath, $generatedView, "La vue [{$destinationPath}] existe déjà. Voulez-vous la remplacer ?");
            }
        }
        $this->comment("");
    }

    private function processRepository()
    {
        $this->info("Étape : Repository");
        if ($this->needCreate || $this->needRead || $this->needUpdate || $this->needDelete) {
            $generatedView = $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/repositories/ModelRepository.stub'));
            $destinationPath = $this->repositoryDirectory . str_replace("Model", $this->modelName, "ModelRepository.php");
            $this->comment("Création du repository `" . $destinationPath . "`");
            $this->createFile($destinationPath, $generatedView, "Le repository [{$destinationPath}] existe déjà. Voulez-vous le remplacer ?");
        }
        $this->comment("");
    }

    private function processDatatableActions()
    {
        $this->info("Étape : Datatable action");
        if ($this->needRead) {
            $generatedView = $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/views/elements/datatable-actions.stub'));
            $destinationPath = $this->viewElementDirectory . "datatable-actions.blade.php";
            $this->comment("Création de la vue `" . $destinationPath . "`");
            $this->createFile($destinationPath, $generatedView, "La vue [{$destinationPath}] existe déjà. Voulez-vous la remplacer ?");
        }
        $this->comment("");
    }

    private function processRoutes()
    {
        $this->info("Étape : Routes");

        $generatedRoutes = $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/routes/group.stub'));
        $adminRoutePath = base_path('routes/admin.php');
        try {
            $adminRouteFile = file_get_contents($adminRoutePath);
            if (strpos($adminRouteFile, '// {{ Helium Crud }}') === false) {
                throw new Exception();
            } else {
                $this->comment("Ajout des routes au fichier `" . $adminRoutePath . "`");
                $adminRouteFile = str_replace('// {{ Helium Crud }}', $generatedRoutes, $adminRouteFile);
                file_put_contents($adminRoutePath, $adminRouteFile);
            }
        } catch (\Throwable $th) {
            $this->error("Le fichier " . $adminRoutePath . " n'existe pas ou ne possède pas la ligne `// {{ Helium Crud }}` qui permet au crud generator de fonctionner");
            $this->comment("Veuillez ajouter manuellement les routes suivantes :");
            $this->info($generatedRoutes);
        }

        $this->comment("");
    }

    private function processMenu()
    {
        $this->info("Étape : Menu");


        $this->menuIcon = $this->askWithCompletion("Icône à utiliser pour le menu : ( https://feathericons.com/ )", FeatherIcons::ICONS, "help-circle");

        $generatedMenu = $this->replaceInStub(file_get_contents(__DIR__ . '/stubs/crud/config/menu.stub'));

        $heliumConfigPath = config_path('helium.php');
        $heliumConfigFile = file_get_contents($heliumConfigPath);
        if (strpos($heliumConfigFile, '// {{ Helium Crud Menu }}') === false) {
            $this->error("Le fichier " . $heliumConfigPath . " ne possède pas la ligne `// {{ Helium Crud Menu }}` qui permet au crud generator de fonctionner");
            $this->comment("Veuillez ajouter manuellement le menu suivant :");
            $this->info($generatedMenu);
        } else {
            $this->comment("Ajout du menu    protected function formatProperties() au fichier `" . $heliumConfigPath . "`");
            $heliumConfigFile = str_replace('// {{ Helium Crud Menu }}', $generatedMenu, $heliumConfigFile);
            file_put_contents($heliumConfigPath, $heliumConfigFile);
        }
        $this->comment("");
    }

    protected function formatProperties()
    {
        $connection = $this->selectedModel->getConnection();
        $databaseName = $connection->getTablePrefix() . $this->selectedModel->getTable();

        $dbProperties = $this->selectedModel->getConnection()->select("select * from INFORMATION_SCHEMA.COLUMNS where table_name = '" . $databaseName . "'");
        // COLUMN_NAME
        // IS_NULLABLE
        // DATA_TYPE

        foreach ($dbProperties as $key => $dbProperty) {
            $temp = (array)$dbProperty;
            $dbProperty = array_combine(array_map('strtolower', array_keys($temp)), $temp);
            if ($dbProperty["table_schema"] != $databaseName && $dbProperty["table_name"] != $databaseName) {
                continue;
            }

            if ($dbProperty["table_schema"] == "performance_schema") { //Mysql hack
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
            $this->error("******************************************************************");
            $this->warn("\t" . count($oldModels) . " models utilisent l'ancien dossier 'Entities‘.");
            $this->warn("\t" . "Veuillez les déplacer dans le dossier 'Models'.");
            $this->error("******************************************************************");
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
            if (in_array($modelProperty['name'], $this->modelFormProperties)) {
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
        }

        return $result;
    }

    protected function createRequestModelProperties()
    {
        $result = [];
        $temp = [];
        foreach ($this->modelProperties as $key => $modelProperty) {
            if (in_array($modelProperty['name'], $this->modelFormProperties)) {
                $temp[array_get($modelProperty, 'name')] = array_get($modelProperty, 'required', false) ? "required" : "";
            }
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
            if (in_array($modelProperty['name'], $this->modelFormProperties)) {
                $result .= '$' . $this->modelNameSingular . '->' . array_get($modelProperty, "name") . ' = array_get($this->data,"' . array_get($modelProperty, "name") . '");' . PHP_EOL;
            }
        }
        return $result;
    }


    protected function getDatatableHtmlCollumns()
    {
        $result = "";
        foreach ($this->modelDataTableProperties as $key => $modelProperty) {
            $result .= "<th>" . $modelProperty . "</th>" . PHP_EOL;
        }
        return $result;
    }
    protected function getDatatableJavacriptCollumns()
    {
        $result = "";
        foreach ($this->modelDataTableProperties as $key => $modelProperty) {
            $result .= "{data: '" . $modelProperty . "', name : '" . $modelProperty . "'}," . PHP_EOL;
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
            '{{ genderPrefix }}' => $this->modelGender == self::WORD_GENDER_NO ? "•e" : ($this->modelGender == self::WORD_GENDER_FEMININE ? "e" : ""),
            '{{ modelGender }}' => $this->modelGender == self::WORD_GENDER_NO ? "un•e" : ($this->modelGender == self::WORD_GENDER_FEMININE ? "une" : "un"),
            '{{ modelGenderDeterministic }}' => in_array(substr($this->userFriendlyNameSingular, 0, 1), ["a", "e", "i", "o", "u", "y"]) ? "l'" : ($this->modelGender == self::WORD_GENDER_FEMININE ? "la " : "le "),
            'de le' => "du",
            '{{ menuIcon }}' => $this->menuIcon,
            '{{ permissions }}' => $this->permissionsString,
        ];

        $extendedReplacers = [
            '{{ IndexDataSetLink }}' => ($this->needUpdate) ? 'row.dataset.link = "{{ helium_route("{{ modelsingular }}.edit", ["id" => "%id%"]) }}".replace("%id%",data.id);' : "",
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
            '{{ DatatableControllerCollumns }}' => implode('", "', $this->modelDataTableProperties),
            '{{ DatatableHtmlCollumns }}' => $this->getDatatableHtmlCollumns(),
            '{{ DatatableJavacriptCollumns }}' => $this->getDatatableJavacriptCollumns(),
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
