<?php

namespace App\Enum;

enum Choices: string
{
    case Rock = 'rock';
    case Paper = 'paper';
    case Scissors = 'scissors';

    function isWin(Choices $opponentChoice): ?bool
    {
        $rules = [
            Choices::Rock->value => Choices::Scissors->value,
            Choices::Paper->value => Choices::Rock->value,
            Choices::Scissors->value => Choices::Paper->value
        ];

        if ($this->value === $opponentChoice->value) {
            return null;
        }

        if ($rules[$this->value] === $opponentChoice->value) {
            return true;
        }

        return false;
    }
}