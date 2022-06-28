<?php

namespace Webup\LaravelHelium\Core\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MigrationController extends Controller
{
    public function index()
    {
        $availableModels = $this->getAvailableModels();
        return view('helium::crud.migration', [
            "availableModels" => $availableModels,
            "oldCustomColumns" => json_decode(old("customColumns", "[]")),
            "oldCustomRelations" => json_decode(old("customRelations", "[]"))
        ]);
    }

    public function post(Request $request)
    {
        $data = $request->all();
        array_set($data, 'enableId', array_get($data, 'enableId') == "on" ? true : false);
        array_set($data, 'enableSoftDelete', array_get($data, 'enableSoftDelete') == "on" ? true : false);
        array_set($data, 'enableTimestamps', array_get($data, 'enableTimestamps') == "on" ? true : false);
        dd($data);
        return redirect()->back()->withInput($data);
        return view('helium::crud.migration');
    }


    private function getAvailableModels()
    {
        $availableModels = [];
        $modelList = $this->getModelsList();
        foreach ($modelList as $modelClass => $model) {
            $modelTableName = $model->getTable();
            $columns = Schema::getColumnListing($modelTableName);
            $availableModels[] = (object)[
                "filepath" => $modelClass . ".php",
                "name" => $modelClass,
                "nameWithoutNamespace" => str_replace("\App\Entities\\", "", $modelClass),
                "nameSingular" => Str::singular($modelTableName),
                "tablename" => $modelTableName,
                "columns" => $columns
            ];
        }

        return $availableModels;
    }

    private function getModelsList()
    {
        $result = [];
        foreach (File::allFiles(app_path("Entities")) as $key => $entity) {
            $classname = str_replace("/srv/http/app/Entities/", "", $entity->getRealPath());
            $classname = str_replace("/", "\\", $classname);
            $classname = str_replace(".php", "", $classname);
            $classnameWithNamespace = "\App\Entities\\" . $classname;
            try {
                $class = new $classnameWithNamespace();
                if (is_subclass_of($class, "Illuminate\Database\Eloquent\Model")) {
                    $selectedModel = new $class();
                    if (Schema::hasTable($selectedModel->getTable())) {
                        $result[$classnameWithNamespace] = $selectedModel;
                    }
                }
            } catch (\Throwable $e) {
            } catch (\Exception $e) {
            }
        }
        return $result;
    }
}
