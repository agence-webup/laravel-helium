<?php

namespace Webup\LaravelHelium\Contact\Http\Controllers;

use anlutro\LaravelSettings\SettingsManager;
use Webup\LaravelHelium\Contact\Mail\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Mail;
use Validator;

class ContactController extends Controller
{
    public function __construct()
    {
        // @todo make a service or a repository
        $this->model = new \Webup\LaravelHelium\Contact\Entities\Contact();
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
     * @param  \anlutro\LaravelSettings\SettingsManager  $settingsManager
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, SettingsManager $settingsManager)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Save messages
        $contact = $this->model::create($validator->getData());

        // Send mail
        Mail::to($settingsManager->get('contact_email'))->send(new ContactMessage($contact));

        // Flash message
        $request->session()->flash('flash.default', [
            'message' => 'Votre message a bien été envoyé.',
            'level' => 'success',
        ]);

        return redirect()->back();
    }

    /**
     * Get a validator to post a message.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required',
        ]);
    }
}
