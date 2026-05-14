<?php

namespace App\Http\Requests\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'name' => [$this->isMethod('post') ? 'required' : 'sometimes', 'string', 'min:3', 'max:255'],
            'email' => [
                $this->isMethod('post') ? 'required' : 'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class, 'email')->ignore($user?->id),
            ],
            'password' => [$this->isMethod('post') ? 'required' : 'nullable', 'string', 'min:8', 'confirmed'],
            'role_ids' => ['sometimes', 'array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
        ];
    }
}
