<?php

namespace Webup\LaravelHelium\Redirection\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webup\LaravelHelium\Redirection\Http\Jobs\StoreRedirection;
use Webup\LaravelHelium\Redirection\Entities\Redirection;
use Webup\LaravelHelium\Redirection\Http\Requests\Admin\Store as StoreRedirectionRequest;
use Webup\LaravelHelium\Core\Facades\HeliumFlash;

class CreateController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('helium::admin.redirection.create', [
            'redirection' => new Redirection(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Webup\LaravelHelium\Redirection\Http\Jobs\Store  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRedirectionRequest $request)
    {
        try {
            $this->dispatchNow(new StoreRedirection($request->validated()));
        } catch (\Exception $e) {
            HeliumFlash::error("Une erreur est survenue.");
            return redirect()->back();
        }

        HeliumFlash::success("Redirection ajoutée avec succès.");

        return redirect()->route('admin.tools.redirection.index');
    }

    public function import()
    {
        return view('helium::admin.redirection.import');
    }

    public function postImport(Request $request)
    {
        $success = 0;
        $errors = 0;
        if (($handle = fopen($request->file('file')->path(), 'r')) !== FALSE) {
            while (($data = fgetcsv($handle, 0, ';')) !== FALSE) {
                DB::transaction(function () use ($data, &$success, &$errors) {
                    try {
                        $this->dispatchNow(new StoreRedirection([
                            "from" => $data[0],
                            "to" => $data[1],
                        ]));
                        $success++;
                    } catch (\Exception $e) {
                        $errors++;
                    }
                });
            }
            fclose($handle);
        }

        if ($success > 0) {
            HeliumFlash::success($success . " redirection(s) ajoutée(s) avec succès.");
        }
        if ($errors > 0) {
            HeliumFlash::error($errors . " erreur(s) d'ajout.");
        }

        return redirect()->route('admin.tools.redirection.index');
    }
}
