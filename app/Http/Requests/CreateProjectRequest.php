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
            'location.name'         => 'required',
            'location.target_hits'  => 'required',
            'name'                  => 'required',
            'user_id'               => 'required',
        ];
    }

    public function messages()
    {
        return [
            'location.name.required'        => 'Area field is required',
            'location.target_hits.required' => 'Target hits field is required',
            'name.required'                 => 'Project name field is required',
            'user_id.required'              => 'Client field is required',
        ];
    }
}
