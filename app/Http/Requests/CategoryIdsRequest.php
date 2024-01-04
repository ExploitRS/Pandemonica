<?php

namespace App\Http\Requests;

class CategoryIdsRequest extends CommonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_ids' => 'sometimes|required|distinct:strict|array:category_id|size:1',
            'category_ids.*' => 'exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'category_ids.required' => 'The category is required',
            'category_ids.distinct' => 'The category must be unique',
            'category_ids.array' => 'The category must be an array',
            'category_ids.size' => 'The category must contain exactly one element',
            'category_ids.*.exists' => 'The category must exist in the database',
        ];
    }
}
