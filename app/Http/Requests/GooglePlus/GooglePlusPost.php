<?php

namespace App\Http\Requests\GooglePlus;

use Illuminate\Foundation\Http\FormRequest;

class GooglePlusPost extends FormRequest
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
            'client_id' => 'required|exists:clients,id',
            'delay' => 'required|numeric|min:0',
            'message' => 'required',
            'isImageUrl' => 'required',
            'queue' => 'required'
        ];
    }
}
