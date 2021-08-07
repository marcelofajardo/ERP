<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMailingListTemplateCategoryRequest extends FormRequest
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
            'name' => 'required|string|unique:mailinglist_template_categories,title'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Category name required.',
            'name.unique' => 'Category name already exists.'
        ];
    }
}
