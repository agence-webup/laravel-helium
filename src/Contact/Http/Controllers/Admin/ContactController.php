<?php

namespace Webup\LaravelHelium\Contact\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webup\LaravelHelium\Contact\Contracts\ContactService;
use Yajra\Datatables\Datatables;

class ContactController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Webup\LaravelHelium\Contact\Contracts\ContactService $contactService
     */
    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('helium::contact.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable()
    {
        $contactQuery = $this->contactService->query()->select('id', 'created_at', 'name');

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
        $contact = $this->contactService->getById($id);
        abort_if(!$contact, 404);

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
        $contact = $this->contactService->getById($id);
        abort_if(!$contact, 404);
        $this->contactService->delete($id);

        // Flash message
        $request->session()->flash('flash.default', [
            'message' => trans('helium::messages.deleted'),
            'level' => 'success',
        ]);

        return redirect()->back();
    }
}
