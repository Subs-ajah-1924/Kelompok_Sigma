<?php

namespace Tests\Unit;

use App\Difficulty;
use PHPUnit\Framework\TestCase;

class DifficultyTest extends TestCase
{
    public function test_weight_increases_with_difficulty(): void
    {
        $this->assertSame(1, Difficulty::Mudah->weight());
        $this->assertSame(2, Difficulty::Sedang->weight());
        $this->assertSame(3, Difficulty::Sulit->weight());
    }

    public function test_label_matches_case_name(): void
    {
        foreach (Difficulty::cases() as $difficulty) {
            $this->assertSame($difficulty->name, $difficulty->label());
        }
    }
}
