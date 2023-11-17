<?php

namespace App\Tests;

use App\Enum\Choices;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    /**
     * @test
     * @dataProvider provider
     */
    public function testGame($userChoice, $oppenentChoice, $result): void
    {
        $this->assertEquals($result, $userChoice->isWin($oppenentChoice));
    }

    public static function provider()
    {
        return [
            'Paper vs Scissors' => [Choices::Paper, Choices::Scissors, false],
            'Paper vs Rock' => [Choices::Paper, Choices::Rock, true],
            'Paper vs Paper' => [Choices::Paper, Choices::Paper, null],

            'Scissors vs Rock' => [Choices::Scissors, Choices::Rock, false],
            'Scissors vs Paper' => [Choices::Scissors, Choices::Paper, true],
            'Scissors vs Scissors' => [Choices::Scissors, Choices::Scissors, null],

            'Rock vs Paper' => [Choices::Rock, Choices::Paper, false],
            'Rock vs Scissors' => [Choices::Rock, Choices::Scissors, true],
            'Rock vs Rock' => [Choices::Rock, Choices::Rock, null],
        ];
    }
}
