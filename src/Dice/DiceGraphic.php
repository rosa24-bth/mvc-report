<?php

namespace App\Dice;

class DiceGraphic extends Dice
{
    private $representation = [
        '⚀',
        '⚁',
        '⚂',
        '⚃',
        '⚄',
        '⚅',
    ];

    public function getAsString(): string
    {
        return $this->representation[$this->value - 1];
    }
}
