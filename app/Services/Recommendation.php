<?php

namespace App\Services;

use App\Priority;

readonly class Recommendation
{
    public function __construct(
        public Priority $priority,
        public int $score,
        public string $reason,
    ) {}

    public static function done(): self
    {
        return new self(
            priority: Priority::Low,
            score: 0,
            reason: 'Tugas sudah selesai.',
        );
    }
}
