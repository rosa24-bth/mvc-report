<?php

namespace App\TwentyOneGame;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Bank.
 */
class BankTest extends TestCase
{
    /**
     * Create a Bank and verify it holds a CardHand.
     */
    public function testBankHasHand()
    {
        $bank = new Bank();
        $this->assertInstanceOf("\App\Card\CardHand", $bank->getHand());
    }

    /**
     * Test if Bank draws at least two cards.
     */
    public function testBankPlayTurnDrawsAtLeastTwoCards()
    {
        $bank = new Bank();
        $deck = new \App\Card\DeckOfCards();
        $deck->shuffle();
        $bank->playTurn($deck);
        $this->assertGreaterThanOrEqual(2, count($bank->getHand()->getCards()));
    }

    /**
     * Test if getScore() returns an integer.
     */
    public function testBankScoreReturnsInteger()
    {
        $bank = new Bank();
        $deck = new \App\Card\DeckOfCards();
        $deck->shuffle();
        $bank->playTurn($deck);

        $score = $bank->getScore();
        $this->assertIsInt($score);
    }
}
