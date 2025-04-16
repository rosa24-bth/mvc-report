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

            if (in_array($value, ['J', 'Q', 'K'])) {
                $total += 10;
            } elseif ($value === 'A') {
                $aces++;
                // Start with ace being 14.
                $total += 14;
            } else {
                $total += (int) $value;
            }
        }

        // If we have more than 21 points and an ace, count ace as 1.
        while ($total > 21 && $aces > 0) {
            $total -= 13;
            $aces--;
        }

        return $total;
    }
}
