<?php

namespace App\Enums;

enum UserRole: string
{
    case Provider = 'provider';
    case Client = 'client';

    public function label(): string
    {
        return match ($this) {
            self::Provider => 'Prestador',
            self::Client => 'Cliente',
        };
    }
}
