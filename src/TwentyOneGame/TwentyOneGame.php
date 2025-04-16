<?php

namespace App\TwentyOneGame;

use App\Card\DeckOfCards;
use App\Card\CardHand;

class TwentyOneGame
{
    private DeckOfCards $deck;
    private CardHand $playerHand;

    public function __construct()
    {
        $this->deck = new DeckOfCards();
        $this->deck->shuffle();
        $this->playerHand = new CardHand();
    }

    public function startGame(): void
    {
        $this->playerHand->addCard($this->deck->drawCard());
        $this->playerHand->addCard($this->deck->drawCard());
    }

    public function drawCard(): void
    {
        $this->playerHand->addCard($this->deck->drawCard());
    }

    public function getPlayerHand(): CardHand
    {
        return $this->playerHand;
    }

    public function getPlayerScore(): int
    {
        return $this->playerHand->getTwentyOneGameValue();
    }

    public function getNumOfRemainingCards(): int
    {
        return count($this->deck->getCards());
    }

    public function getCurrentDeck(): DeckOfCards
    {
        return $this->deck;
    }
}
