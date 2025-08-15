<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ];
    }

    
    public function messages(): array
    {
        return [
            'name.required' => 'The team name is required.',
            'name.max' => 'The team name may not be greater than 255 characters.',
            'description.max' => 'The description may not be greater than 1000 characters.',
        ];
    }
}
