<?php

namespace Webup\LaravelHelium\Contact\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webup\LaravelHelium\Contact\Contracts\ContactService;

class ContactController extends Controller
{
    protected $contactService;

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
     * Display a contact form.
     *
     * @return \Illuminate\Http\Response
     */
    public function form()
    {
        return view('helium::web.contact');
    }

    /**
     * Store a new contact message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->contactService->create($request->all());

        // Flash message
        $request->session()->flash('flash.default', [
            'message' => 'Votre message a bien été envoyé.',
            'level' => 'success',
        ]);

        return redirect()->back();
    }
}
