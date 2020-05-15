<?php

namespace Webup\LaravelHelium\Redirection\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Webup\LaravelHelium\Redirection\Entities\Redirection;
use DB;

class IndexController extends Controller
{
    public function index()
    {
        return view('helium::admin.redirection.index');
    }

    public function datatable(Request $request)
    {
        $redirections = Redirection::select("id", "from", "to");

        return Datatables::of($redirections)
            ->rawColumns(['actions'])
            ->addColumn('actions', function ($redirection) {
                return view('helium::admin.redirection.elements.datatable-actions', ['redirection' => $redirection])->render();
            })
            ->make(true);
    }
}
