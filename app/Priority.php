<?php

namespace App;

enum Priority: string
{
    case High = 'high';
    case Medium = 'medium';
    case Low = 'low';

    public function label(): string
    {
        return match ($this) {
            self::High => 'Prioritas Tinggi',
            self::Medium => 'Prioritas Sedang',
            self::Low => 'Prioritas Rendah',
        };
    }
}
