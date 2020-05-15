<?php

namespace Webup\LaravelHelium\Redirection\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Webup\LaravelHelium\Redirection\Http\Jobs\UpdateRedirection;
use Webup\LaravelHelium\Redirection\Entities\Redirection;
use Webup\LaravelHelium\Redirection\Http\Requests\Admin\Update as UpdateRedirectionRequest;

class EditController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $redirection = Redirection::findOrFail($id);

        return view('helium::admin.redirection.edit', [
            'redirection' => $redirection,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Webup\LaravelHelium\Redirection\Http\Jobs\Update  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRedirectionRequest $request, $id)
    {
        try {
            $this->dispatchNow(new UpdateRedirection($id, $request->validated()));
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
            'message' => "Modifications enregistrées avec succès.",
            'level' => 'success',
        ]);

        return redirect()->route('admin.redirection.index');
    }
}
