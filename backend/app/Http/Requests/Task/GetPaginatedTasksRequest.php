<?php

namespace App\Http\Requests\Task;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetPaginatedTasksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => 'required|integer|min:1',
            'page_size' => 'required|integer|min:1|max:100',
            'sort_by' => 'required|string|in:title,description,completed,due_date,created_at,updated_at',
            'sort_type' => 'required|string|in:asc,desc',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'detail' => $validator->errors()->first() ?? 'Validation error',
        ], 422));
    }
}
