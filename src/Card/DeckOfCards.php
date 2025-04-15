<?php

namespace App\Card;

class DeckOfCards
{
    private array $cards = [];

    public function __construct()
    {
        $suits = ['♠', '♥', '♦', '♣'];
        $ranks = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

        foreach ($suits as $suit) {
            foreach ($ranks as $rank) {
                $this->cards[] = new CardGraphic($rank, $suit);
            }
        }
    }

    public function getCards(): array
    {
        return $this->cards;
    }
}
