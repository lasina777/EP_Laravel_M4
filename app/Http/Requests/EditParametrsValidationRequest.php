<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditParametrsValidationRequest extends FormRequest
{
    /**
     * Определите, авторизован ли пользователь для выполнения этого запроса.
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Правила проверки, применимые к запросу.
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('telegram_settings', 'name')
                    ->ignore($this->route('telegramSetting'))
            ],
            'val' => 'required'
        ];
    }

    /**
     * Сообщение об ошибке на русском языке для полей
     * @return array|string[]
     */
    public function messages()
    {
        return parent::messages() + [
                'name.unique' => 'Имя :input уже существует',
                'name.required' => 'Поле обязательно для заполнения наименования',
                'val.required' => 'Поле обязательно для заполнения значения',
            ]; // TODO: Change the autogenerated stub
    }
}
