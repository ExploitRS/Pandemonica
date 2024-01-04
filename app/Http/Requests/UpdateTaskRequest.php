<?php

namespace App\Http\Requests;

class UpdateTaskRequest extends TaskRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string|max:65535',
            'due_date' => 'sometimes|nullable|date|after_or_equal:today',
        ];
    }
}
