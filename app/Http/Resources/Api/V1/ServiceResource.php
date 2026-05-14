<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'code' => $this->code,
            'address_type' => [
                'value' => $this->address_type?->value,
                'label' => $this->address_type?->label(),
            ],
            'address' => $this->address,
            'number' => $this->number,
            'complement' => $this->complement,
            'city' => $this->city,
            'state' => $this->state,
            'postal' => $this->postal,
            'description' => $this->description,
            'status' => [
                'value' => $this->status?->value,
                'label' => $this->status?->label(),
            ],
            'images' => ServiceImageResource::collection($this->whenLoaded('images')),
            'date_start' => $this->date_start?->toDateString(),
            'date_end' => $this->date_end?->toDateString(),
            'hour_start' => $this->hour_start?->format('H:i'),
            'hour_end' => $this->hour_end?->format('H:i'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
