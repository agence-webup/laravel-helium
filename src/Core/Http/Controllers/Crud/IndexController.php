<?php

namespace Webup\LaravelHelium\Core\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class IndexController extends Controller
{
    public function index()
    {
        return view('helium::crud.index', [
            "models" => $this->getModelsList()
        ]);
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
