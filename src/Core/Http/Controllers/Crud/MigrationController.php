<?php

namespace Webup\LaravelHelium\Core\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Webup\LaravelHelium\Core\Helpers\CrudHelper;

class MigrationController extends Controller
{
    public function index()
    {
        return view('helium::crud.migration', [
            "models" => $this->getModelsList()
        ]);
    }

    public function post(Request $request)
    {
        $data = $request->validate([
            "migration" => "",
            "model" => "required_without:migration",
            "name" => "required_with:migration,on",
        ]);

        if (array_get($data, "migration", false) != false) {
            $name = array_get($data, "name");
            // Artisan::call('make:migration create_' . Str::plural($name) . '_table');

            CrudHelper::replaceInStubAndSave('/entities/model.stub', [
                'Model' => ucfirst(Str::singular($name))
            ], app_path('Entities/' . ucfirst(Str::singular($name)) . ".php"));
            dd("create migration");
        } else {
            dd($data);
        }
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
                    $result[$classnameWithNamespace] = $classname;
                }
            } catch (\Throwable $e) {
            } catch (\Exception $e) {
            }
        }
        return $result;
    }
}
