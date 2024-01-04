<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends CommonRequest 
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:65535',
            'due_date' => 'nullable|date|after_or_equal:today',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'A title is required',
            'title.max' => 'A title cannot be longer than 255 characters',
            'description.max' => 'A description cannot be longer than 65535 characters',
            'due_date.date' => 'The due date must be a valid date',
            'due_date.after_or_equal' => 'The due date must be today or later',
        ];
    }
}
