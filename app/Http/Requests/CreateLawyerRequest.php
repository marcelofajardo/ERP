<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLawyerRequest extends FormRequest
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
            'speciality_id'   => 'required|numeric',
            'name'          => 'required|string|max:255',
            'address'       => 'sometimes|nullable|string',
            'phone'         => 'sometimes|nullable|numeric',
            'email'         => 'sometimes|nullable|email',
        ];
    }
}
