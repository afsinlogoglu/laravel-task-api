<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'sometimes|in:pending,in_progress,completed',
            'assigned_user_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after:today',
            'team_id' => 'required|exists:teams,id',
        ];
    }

    
    public function messages(): array
    {
        return [
            'title.required' => 'The task title is required.',
            'title.max' => 'The task title may not be greater than 255 characters.',
            'description.max' => 'The description may not be greater than 1000 characters.',
            'status.in' => 'The status must be one of: pending, in_progress, completed.',
            'assigned_user_id.exists' => 'The assigned user does not exist.',
            'due_date.date' => 'The due date must be a valid date.',
            'due_date.after' => 'The due date must be a future date.',
            'team_id.required' => 'The team is required.',
            'team_id.exists' => 'The selected team does not exist.',
        ];
    }
}
