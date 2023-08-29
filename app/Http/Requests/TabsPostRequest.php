<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TabsPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(
            response() -> json([
                'error' => $validator -> errors()
            ], 422)
        );
    }

    public function rules(): array
    {
        return [
            'header' => 'required|string|max:127',
            'content' => 'required|string|max:255',
            'parent_id' => 'nullable|numeric'
        ];
    }   
}
