<?php

namespace Database\Factories;

use App\Enums\AdressType;
use App\Enums\Status;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'code' => strtoupper(fake()->unique()->bothify('SRV-###??')),
            'address_type' => fake()->randomElement(array_column(AdressType::cases(), 'value')),
            'address' => fake()->streetAddress(),
            'postal' => fake()->postcode(),
            'description' => fake()->sentence(),
            'status' => fake()->randomElement(array_column(Status::cases(), 'value')),
            'date_start' => fake()->date(),
            'date_end' => fake()->date(),
            'hour_start' => '08:00',
            'hour_end' => '17:00',
        ];
    }
}
