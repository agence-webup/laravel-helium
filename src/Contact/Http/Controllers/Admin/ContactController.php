<?php

namespace Webup\LaravelHelium\Contact\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Yajra\Datatables\Datatables;

class ContactController extends Controller
{
    public function __construct()
    {
        // @todo make a service or a repository
        $this->model = new \Webup\LaravelHelium\Contact\Entities\Contact();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('helium::contact.index', [
            'contacts' => $this->model::orderBy('created_at', 'desc')->paginate(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable()
    {
        $contactQuery = $this->model::select('id', 'created_at', 'name');

        return Datatables::of($contactQuery)
            ->editColumn('created_at', function ($contact) {
                return $contact->created_at->format('d/m/Y H:i');
            })
            ->addColumn('actions', function ($contact) {
                return view('helium::contact.datatable-actions', ['contact' => $contact])->render();
            })
            ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = $this->model::findOrFail($id);

        return view('helium::contact.show', [
            'message' => $contact,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->model::findOrFail($id);
        $this->model::destroy($id);

        // Flash message
        $request->session()->flash('flash.default', [
            'message' => trans('helium::messages.deleted'),
            'level' => 'success',
        ]);

        return redirect()->back();
    }
}
