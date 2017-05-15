<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAccountRequest extends FormRequest
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
            'profile.first_name'  => 'required',
            'profile.last_name'   => 'required',
            'email'               => 'required',
            'profile.birthdate'   => 'required',
            'password'            => 'required|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'profile.first_name.required' => 'First name field is required',
            'profile.last_name.required' => 'Last name field is required',
            'profile.birthdate.required' => 'Birthdate field is required',
        ];
    }
}
