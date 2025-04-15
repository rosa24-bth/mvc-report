<?php

namespace App\Card;

class CardHand
{
    private array $hand = [];

    public function addCard(CardGraphic $card): void
    {
        $this->hand[] = $card;
    }

    public function getCards(): array
    {
        return $this->hand;
    }

    public function clear(): void
    {
        $this->hand = [];
    }
}
