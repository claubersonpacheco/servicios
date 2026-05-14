<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\AdressType;
use App\Enums\Status;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $service = $this->route('service');

        return [
            'user_id' => [$this->isMethod('post') ? 'required' : 'sometimes', 'integer', 'exists:users,id'],
            'code' => [
                $this->isMethod('post') ? 'required' : 'sometimes',
                'string',
                'max:255',
                Rule::unique(Service::class, 'code')->ignore($service?->id),
            ],
            'address_type' => [$this->isMethod('post') ? 'required' : 'sometimes', Rule::in(array_column(AdressType::cases(), 'value'))],
            'address' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:255'],
            'complement' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => [$this->isMethod('post') ? 'required' : 'sometimes', Rule::in(array_column(Status::cases(), 'value'))],
            'date_start' => ['nullable', 'date'],
            'date_end' => ['nullable', 'date', 'after_or_equal:date_start'],
            'hour_start' => ['nullable', 'date_format:H:i'],
            'hour_end' => ['nullable', 'date_format:H:i'],
        ];
    }
}
