<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarcajesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'entrance' => 'required',
            'check_in_time' => 'required',
            'nature_of_work' => 'required',
            'exit' => 'required',
            'departure_time' => 'required',
        ];
    }
}
