<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CreateQuestionnaireRequest extends FormRequest
{
    /** @var null  */
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
            'category_id' => 'required|integer',
            'photo'       => 'image|mimes:jpeg,jpg,png,webp',
            'name'        => 'required|string',
            'age'         => 'required|numeric|between:18,60',
            'height'      => 'required|numeric',
            'weight'      => 'required|numeric',
            'breast_size' => 'required|integer|between:0,5',
            'hair_color_id'  => 'required|integer',
            'body_type_id'   => 'required|integer',
            'eyes_color_id'  => 'required|integer',
            'orientation_id' => 'required|integer',
            'nationality_id' => 'required|integer',
            'region_id'      => 'required|integer',
            'phone'          => 'required|string|regex:/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/',
            'about_yourself' => 'max:255',

            //information of tariffs
            'cost_apartment_one_hour' => 'numeric|nullable',
            'cost_apartment_two_hour' => 'numeric|nullable',
            'cost_apartment_night'    => 'numeric|nullable',
            'cost_departure_one_hour' => 'numeric|nullable',
            'cost_departure_two_hour' => 'numeric|nullable',
            'cost_departure_night'    => 'numeric|nullable',
            'confirm' => 'accepted',
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
            'name.required'  => 'Введите имя',
            'phone.required' => 'Введите телефон',

            'about_yourself.max' => 'Максимальная длина сообщения 255 символов',

            'age.required' => 'Введите возраст',
            'age.numeric'  => 'Возраст введен некорректно',
            'age.between'  => 'Возраст должен быть от 18 лет',

            'height.required'      => 'Введите рост',
            'height.numeric'       => 'Рост введен некорректно',
            'weight.required'      => 'Введите вес',
            'weight.numeric'       => 'Вес введен некорректно',

            'photo.image' => 'Файл должен быть изображением',
            'photo.mimes' => 'Некорректный формат',
            'phone.regex' => 'Некорректный номер телефона',
            'confirm.accepted' => 'Подтвердите что что вы ознакомлены с правилами сайта'
        ];
    }
}
