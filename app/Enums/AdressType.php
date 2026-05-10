<?php

namespace App\Enums;

enum AdressType: int
{
    case CALLE = 1;
    case AVENIDA = 2;
    case PASEO = 3;

    public function label(): string
    {
        return match ($this) {
            self::CALLE => 'Calle',
            self::AVENIDA => 'Avenida',
            self::PASEO => 'Paseo',
        };
    }

    public static function options(): array
    {
        return array_map(fn($e) => [
            'value' => $e->value,
            'label' => $e->label(),
        ], self::cases());
    }
}
