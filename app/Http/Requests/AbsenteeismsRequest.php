<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AbsenteeismsRequest extends FormRequest
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
            'withdrawal_date' => 'required',
            'discharge_date' => 'required',
            'absenteeism_days' => 'numeric',
            'holidays_days' => 'numeric',
            'id_absence' => 'required|exists:absences,id',
        ];
    }
}
