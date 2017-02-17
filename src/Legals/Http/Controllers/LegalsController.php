<?php

namespace Webup\LaravelHelium\Legals\Http\Controllers;

use anlutro\LaravelSettings\SettingsManager;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Validator;

class LegalsController extends Controller
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
    public function index()
    {
        return view('helium::web.legals', [
            'legals' => $this->settingsManager->get('legals')
        ]);
    }
}
