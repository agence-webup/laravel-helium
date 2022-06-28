<?php

namespace Webup\LaravelHelium\Redirection\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Webup\LaravelHelium\Redirection\Http\Jobs\UpdateRedirection;
use Webup\LaravelHelium\Redirection\Entities\Redirection;
use Webup\LaravelHelium\Redirection\Http\Requests\Admin\Update as UpdateRedirectionRequest;
use Webup\LaravelHelium\Core\Facades\HeliumFlash;

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
        } catch (\Exception $e) {
            HeliumFlash::error("Une erreur est survenue.");
            return redirect()->back()
                ->withInput($request->input());
        }

        HeliumFlash::success("Redirection modifiée avec succès.");

        return redirect()->route(helium_route_name('tools.redirection.index'));
    }
}
