<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class RegistrationRequest extends FormRequest
{
    /** @var null  */
    public $validator = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'login' => "required|min:4|unique:users,login",
            'email'             => "required|email|unique:users,email",
            'password'  => "required|confirmed|min:6",
            'checker'   => "accepted",
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'login.required' => 'Поле логин не заполнено',
            'login.min'      => 'Логин должен быть от 4 символов',
            'login.unique'   => 'Логин уже занят',

            'email.required' => 'Поле email не заполнено',
            'email.email'    => 'Некорректный email',
            'email.unique'   => 'Email уже занят',

            'password.required'  => 'Поле пароль не заполнено',
            'password.confirmed' => 'Пароли не совпадают',
            'password.min'       => 'Пароль должен быть от 6 до 30 символов',

            'checker.accepted' => 'Нажмите принять правила сайта ',
        ];
    }
}
