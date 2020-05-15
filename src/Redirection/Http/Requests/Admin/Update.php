<?php

namespace Webup\LaravelHelium\Redirection\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class Update extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "from" => "required",
            "to" => "required"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('notif.default', [
            'message' => "Les données sont invalides.",
            'level' => 'error',
        ]);

        parent::failedValidation($validator);
    }
}
