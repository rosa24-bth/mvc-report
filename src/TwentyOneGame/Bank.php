<?php

namespace App\TwentyOneGame;

use App\Card\CardHand;
use App\Card\DeckOfCards;

class Bank
{
    private CardHand $hand;

    public function __construct()
    {
        $this->hand = new CardHand();
    }

    public function getHand(): CardHand
    {
        return $this->hand;
    }

    public function playTurn(DeckOfCards $deck): void
    {
        $this->hand->addCard($deck->drawCard());
        $this->hand->addCard($deck->drawCard());

        // Bank keeps on drawing until it has a tleast 17.
        while ($this->hand->getTwentyOneGameValue() < 17) {
            $this->hand->addCard($deck->drawCard());
        }
    }

    public function getScore(): int
    {
        return $this->hand->getTwentyOneGameValue();
    }
}
