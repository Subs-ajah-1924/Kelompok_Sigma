<?php

namespace App;

enum Difficulty: string
{
    case Mudah = 'mudah';
    case Sedang = 'sedang';
    case Sulit = 'sulit';

    public function label(): string
    {
        return match ($this) {
            self::Mudah => 'Mudah',
            self::Sedang => 'Sedang',
            self::Sulit => 'Sulit',
        };
    }

    public function weight(): int
    {
        return match ($this) {
            self::Mudah => 1,
            self::Sedang => 2,
            self::Sulit => 3,
        };
    }
}
