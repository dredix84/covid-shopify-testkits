<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $outValue = [
            'name'  => 'required|min:3',
            'email' => 'required|email'
        ];

        if (blank($this->is_admin) || $this->is_admin === false) {
            $outValue['pickup_locations'] = "array|min:1";
        }
        if (!blank($this->password)) {
            $outValue['password'] = [
                "min:8",
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                'required_with:password_confirmation',
                'same:password_confirmation'
            ];
        }

        return $outValue;
    }

    public function messages()
    {
        return [
            'password.regex' => 'Password must include at least 1 lowercase letter, 1 uppercase letter, a number and a non-alphanumeric (example: !, $, #, or %)'
        ];
    }
}
