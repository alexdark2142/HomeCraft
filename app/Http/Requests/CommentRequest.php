<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /** @var null */
    public $validator = null;

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
            'questionnaire_id' => 'required|integer',
            'name' => 'required|string|max:20',
            'message' => 'required|string|max:500'
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
            'name.required' => 'Введите  имя',
            'name.max' => 'Максимальная длина имени 20 символов',
            'message.required' => 'Введите сообщения',
            'message.max' => 'Максимальная длина сообщения 500 символов',
        ];

    }
}
