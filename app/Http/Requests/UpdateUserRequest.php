<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'unique:users,email,' . $this->route('user')->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'department' => ['nullable', 'string', 'max:100'],
            'is_active' => ['boolean'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ];
    }
}
