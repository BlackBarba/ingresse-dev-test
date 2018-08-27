<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class User extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|regex:/^[a-zA-Z0-9._-]+$/|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'string|max:255',
            'birthday' => 'nullable|date'
        ];

        if ($this->method() == 'POST') {
            $rules['password'] .= '|required';
        } elseif ($this->method() == 'PUT') {
            $user = $this->route('user');
            $rules['email'] .= ',email,'.$user;
            $rules['username'] .= ',username,'.$user;
        }

        return $rules;
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages()
    {
        return [
            'username.regex' => 'The username format is invalid. The username must only contain characters alphanumeric, dots, underscores and dashes. '
        ];
    }
}