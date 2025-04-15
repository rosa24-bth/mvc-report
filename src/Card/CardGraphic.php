<?php

namespace App\Card;

class CardGraphic extends Card
{
    public function __toString(): string
    {
        return $this->getAsString();
    }
}
