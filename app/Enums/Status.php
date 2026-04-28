<?php

namespace App\Enums;

enum Status: int
{
    case ABIERTO = 2;
    case EN_PROCESO = 1;
    case FINALIZADO = 0;

    public function label(): string
    {
        return match ($this) {
            self::ABIERTO => 'Abierto',
            self::EN_PROCESO => 'En proceso',
            self::FINALIZADO => 'Finalizado',
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
