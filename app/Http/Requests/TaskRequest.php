<?php

namespace App\Http\Requests;

class TaskRequest extends CommonRequest
{
    public function messages()
    {
        return [
            'title.required' => 'The title is required',
            'title.max' => 'The title cannot be longer than 255 characters',
            'description.max' => 'The description cannot be longer than 65535 characters',
            'due_date.date' => 'The due date must be a valid date',
            'due_date.after_or_equal' => 'The due date must be today or later',
        ];
    }
}
