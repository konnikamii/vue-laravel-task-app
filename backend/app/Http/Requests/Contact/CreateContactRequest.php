<?php

namespace App\Http\Requests\Contact;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateContactRequest extends FormRequest
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
            'name' => 'required|string|max:255|regex:/[a-zA-Z]/',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255|regex:/[a-zA-Z]/',
            'message' => 'required|string|max:1500|regex:/[a-zA-Z]/',
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
