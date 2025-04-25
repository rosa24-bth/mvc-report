<?php

namespace App\Card;

class DeckOfCards
{
    private array $cards = [];

    public function __construct()
    {
        $suits = ['♠️', '♥️', '♦️', '♣️'];
        $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->cards[] = new CardGraphic($value, $suit);
            }
        }
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function getCardsAsString(): array
    {
        $strings = [];

        foreach ($this->cards as $card) {
            $strings[] = (string) $card;
        }

        return $strings;
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    public function drawCard()
    {
        return array_pop($this->cards);
    }

    public function drawCards(int $number): array
    {
        $drawn = [];

        for ($i = 0; $i < $number; $i++) {
            $card = array_pop($this->cards);
            if ($card !== null) {
                $drawn[] = $card;
            }
        }

        return $drawn;
    }
}
