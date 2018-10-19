<?php

namespace App\Http\Requests\Api;

class UserRegisterRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|confirmed|min:6',
            'email' => 'required|email|unique:users',
            'username' => 'required|max:20|min:6|unique:users',
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'unique' => config('code.user.email_exists'),
        ]);
    }
}
