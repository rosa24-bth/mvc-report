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

    public function getTwentyOneGameValue(): int
    {
        $total = 0;
        $aces = 0;

        foreach ($this->hand as $card) {
            $value = $card->getValue();

            if ($value === 'J') {
                $total += 11;
                continue;
            }

            if ($value === 'Q') {
                $total += 12;
                continue;
            }

            if ($value === 'K') {
                $total += 13;
                continue;
            }

            if ($value === 'A') {
                $aces++;
                $total += 14;
                continue;
            }

            $total += (int) $value;
        }

        // If total is over 21 and we have aces then count them as 1 instead of 14.
        while ($total > 21 && $aces > 0) {
            $total -= 13;
            $aces--;
        }

        return $total;
    }
}
