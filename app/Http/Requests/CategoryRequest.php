<?php

namespace App\Http\Requests;

class CategoryRequest extends CommonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'label' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'label.required' => 'The label is required',
            'label.max' => 'The label cannot be longer than 255 characters',
        ];
    }
}
