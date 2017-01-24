<?php

namespace Webup\LaravelHelium\Contact\Services;

use anlutro\LaravelSettings\SettingsManager;
use Illuminate\Validation\ValidationException;
use Mail;
use Validator;
use Webup\LaravelHelium\Contact\Contracts\ContactService as ContactServiceContract;
use Webup\LaravelHelium\Contact\Mail\ContactMessage;

class ContactService implements ContactServiceContract
{
    protected $model;
    protected $settingsManager;

    /**
     * Create a new contact service.
     *
     * @param  \anlutro\LaravelSettings\SettingsManager  $settingsManager
     * @param  string  $model
     */
    public function __construct(SettingsManager $settingsManager, $model)
    {
        $this->settingsManager = $settingsManager;
        $this->model = $model;
    }

    /**
     * Get a new query builder for the contat model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->createModel()->query();
    }

    /**
     * Get a contact model by its primary key.
     *
     * @param  mixed  $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getById($id)
    {
        return $this->createModel()->find($id);
    }

    /**
     * Create a new contat message.
     *
     * @param  array  $data
     */
    public function create(array $data)
    {
        $validator = $this->validator($data);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Save messages
        $contact = $this->createModel()->create($validator->getData());

        // Send mail
        Mail::to($this->settingsManager->get('contact_email'))->send(new ContactMessage($contact));
    }

    /**
     * Delete a contact message.
     *
     * @param  mixed  $id
     */
    public function delete($id)
    {
        $this->createModel()->destroy($id);
    }

    /**
     * Create a new instance of the model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function createModel()
    {
        $class = '\\'.ltrim($this->model, '\\');

        return new $class;
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
