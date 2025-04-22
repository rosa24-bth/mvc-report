<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Test creating a card and checking its value.
     */
    public function testCardValue()
    {
        $card = new Card("8", "♠");
        $this->assertEquals("8", $card->getValue());
    }

    /**
     * Test creating a card and checking its suit.
     */
    public function testCardSuit()
    {
        $card = new Card("8", "♠");
        $this->assertEquals("♠", $card->getSuit());
    }

    /**
     * Test that getAsString() returns string.
     */
    public function testCardStringFormat()
    {
        $card = new Card("8", "♠");
        $str = $card->getAsString();

        $this->assertStringContainsString("8", $str);
        $this->assertStringContainsString("♠", $str);
    }
}
