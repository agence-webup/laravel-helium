<?php

namespace Webup\LaravelHelium\Redirection\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Webup\LaravelHelium\Redirection\Http\Jobs\DestroyRedirection;
use Webup\LaravelHelium\Redirection\Entities\Redirection;

class DestroyController extends Controller
{
    public function destroy($id)
    {
        try {
            $this->dispatchNow(new DestroyRedirection($id));
        } catch (ValidationException $e) {
            return redirect()->back();
        }

        session()->flash('notif.default', [
            'message' => "Redirection supprimée avec succès.",
            'level' => 'success',
        ]);

        return redirect()->route('admin.redirection.index');
    }

    public function destroyAll()
    {
        Redirection::truncate();
        session()->flash('notif.default', [
            'message' => "Redirections supprimées avec succès.",
            'level' => 'success',
        ]);
        return redirect()->route('admin.redirection.index');
    }
}
