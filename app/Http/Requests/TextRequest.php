<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class TextRequest
 * @package App\Http\Requests
 */
class TextRequest extends FormRequest
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
            'text' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'Необходимо заполнить текст',
        ];
    }
}
