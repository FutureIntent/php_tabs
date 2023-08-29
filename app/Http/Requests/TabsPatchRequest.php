<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TabsPatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'header' => 'string|max:127',
            'content' => 'string|max:255',
            'parent_id' => 'nullable|numeric'
        ];
    }
}
