<?php

namespace Webup\LaravelHelium\Redirection\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webup\LaravelHelium\Redirection\Http\Jobs\StoreRedirection;
use Webup\LaravelHelium\Redirection\Entities\Redirection;
use Webup\LaravelHelium\Redirection\Http\Requests\Admin\Store as StoreRedirectionRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
        } catch (ValidationException $e) {
            session()->flash('notif.default', [
                'message' => "Une erreur est survenue.",
                'level' => 'error',
            ]);

            return redirect()->back()
                ->withInput($request->input())
                ->withErrors($e->validator->errors());
        } catch (\Exception $e) {
            session()->flash('notif.default', [
                'message' => "Une erreur est survenue.",
                'level' => 'error',
            ]);

            return redirect()->back();
        }

        $request->session()->flash('notif.default', [
            'message' => "Redirection ajoutée avec succès.",
            'level' => 'success',
        ]);

        return redirect()->route('admin.redirection.index');
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
            $request->session()->flash('notif.default', [
                'message' => $success . " redirection(s) ajoutée(s) avec succès.",
                'level' => 'success',
            ]);
        } else {
            $request->session()->flash('notif.default', [
                'message' => $errors . " erreur(s) d'ajout.",
                'level' => 'error',
            ]);
        }




        return redirect()->route('admin.redirection.index');
    }
}
