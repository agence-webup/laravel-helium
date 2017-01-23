<?php

namespace Webup\LaravelHelium\Setting\Http\Controllers;

use anlutro\LaravelSettings\SettingsManager;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Validator;

class SettingController extends Controller
{
    private $settingsManager;

    public function __construct(SettingsManager $settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }

    /**
     * Display a form to edit settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('helium::setting.edit', [
            'settings' => $this->settingsManager
        ]);
    }

    /**
     * Update settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = $this->validator($request->all());

        // Remove unvalidated data
        $validator->after(function ($validator) {
            if ($validator->errors()->count()) {
                return;
            }
            $data = $validator->getData();
            foreach ($data as $key => $value) {
                if (!array_key_exists($key, $validator->getRules())) {
                    unset($data[$key]);
                }
            }
            $validator->setData($data);
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Save settings
        $data = $validator->getData();
        foreach ($data as $key => $value) {
            $this->settingsManager->set($key, $value);
        }
        $this->settingsManager->save();

        // Flash message
        $request->session()->flash('flash.default', [
            'message' => trans('helium::messages.saved'),
            'level' => 'success',
        ]);

        return redirect()->route('admin.setting.edit');
    }

    /**
     * Get a validator to update settings.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'contact_email' => 'required|email|max:255',
        ]);
    }
}
