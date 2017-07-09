<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProjectRequest extends FormRequest
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
            'locations.*.name'           => 'required',
            'locations.*.date'           => 'required',
            'name'                      => 'required',
            'user_id'                   => 'required',
        ];
    }

    public function messages()
    {
        return [
            'locations.*.name'   => [
                'required'        => 'Area field is required'
            ],
            'locations.*.target_hits'    => [
                'required' => 'Target hits field is required'
            ],
            'locations.*.date'    => [
                'required' => 'Date field is required'
            ],
            'name.required'                 => 'Project name field is required',
            'user_id.required'              => 'Client field is required',
        ];
    }
}
