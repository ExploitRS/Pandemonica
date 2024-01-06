<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

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
            'category_ids' => 'required|distinct:strict|array|size:1',
            'category_ids.*.category_id' => 'integer|min:1|exists:categories,id',
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
            'category_ids.*.category_id.exists' => 'The category must exist in the database',
            'category_ids.*.category_id.integer' => 'The category must be an integer',
            'category_ids.*.category_id.min' => 'The category must be at least 1',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = [];
        $msg = $validator->errors()->getMessages();
        foreach ($msg as $key => $error) {
            if (preg_match('/^category_ids\.\d+\.category_id$/', $key)) {
                $errors['category'] = $error;
            } else {
                $errors[$key] = $error;
            }
        }
        $response = ['message' => 'The given data was invalid.', 'errors' => $errors];

        throw new HttpResponseException(response()->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
