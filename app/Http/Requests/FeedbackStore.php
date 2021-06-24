<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackStore extends FormRequest
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
            'antigen_tests_administered'         => 'numeric|min:1',
            'employee_participating' => 'numeric'
        ];
    }

    public function messages()
    {
        return [
            'antigen_tests_administered.min'         => 'The rapid antigen tests administered must be at least 1.',
        ];
    }
}
