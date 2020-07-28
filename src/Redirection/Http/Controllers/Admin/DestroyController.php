<?php

namespace Webup\LaravelHelium\Redirection\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Webup\LaravelHelium\Redirection\Http\Jobs\DestroyRedirection;
use Webup\LaravelHelium\Redirection\Entities\Redirection;
use Webup\LaravelHelium\Core\Facades\HeliumFlash;

class DestroyController extends Controller
{
    public function destroy($id)
    {
        try {
            $this->dispatchNow(new DestroyRedirection($id));
        } catch (\Exception $e) {
            HeliumFlash::error("Impossible de supprimer la redirection.");
            return redirect()->back();
        }
        HeliumFlash::success("Redirection supprimée avec succès.");

        return redirect()->route('admin.tools.redirection.index');
    }

    public function destroyAll()
    {
        try {
            Redirection::truncate();
        } catch (\Exception $e) {
            HeliumFlash::error("Impossible de supprimer les redirections.");
            return redirect()->back();
        }
        HeliumFlash::success("Redirections supprimées avec succès.");

        return redirect()->route('admin.tools.redirection.index');
    }
}
