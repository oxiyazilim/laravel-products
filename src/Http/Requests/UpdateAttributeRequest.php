<?php

namespace Speelpenning\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttributeRequest extends FormRequest
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
            'description' => ['required', 'string', 'unique:attributes,description'.$this->route('attribute'), 'max:40'],

            'maxlength' => ['integer', 'between:1,255'],
            'autocomplete' => ['string', 'in:off'],
            'placeholder' => ['string', 'max:255'],
            'pattern' => ['string', 'max:255'],

            'min' => ['numeric'],
            'max' => ['numeric'],
            'step' => ['numeric'],
            'unitOfMeasurement' => ['string', 'max:20'],
        ];
    }
}
