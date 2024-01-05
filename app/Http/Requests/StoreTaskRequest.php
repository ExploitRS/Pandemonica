<?php

namespace App\Http\Requests;

class StoreTaskRequest extends TaskRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:65535',
            'due_date' => 'nullable|date|after_or_equal:today',
        ]);
    }
}
