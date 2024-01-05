<?php

namespace App\Http\Requests;

class TaskRequest extends CategoryIdsRequest
{

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'category_ids' => 'sometimes|required|distinct:strict|array|size:1',
            'category_ids.*.category_id' => 'integer|min:1|exists:categories,id',
        ]);
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'title.required' => 'The title is required',
            'title.max' => 'The title cannot be longer than 255 characters',
            'description.max' => 'The description cannot be longer than 65535 characters',
            'due_date.date' => 'The due date must be a valid date',
            'due_date.after_or_equal' => 'The due date must be today or later',
        ]);
    }
}
