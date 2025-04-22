<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for the CardHand class.
 */
class CardHandTest extends TestCase
{
    /**
     * Test that cards can be added from the hand.
     */
    public function testAddCards()
    {
        $hand = new CardHand();
        $card1 = new CardGraphic("2", "♠");
        $card2 = new CardGraphic("Q", "♥");

        $hand->addCard($card1);
        $hand->addCard($card2);

        $cards = $hand->getCards();
        $this->assertCount(2, $cards);
        $this->assertSame($card1, $cards[0]);
        $this->assertSame($card2, $cards[1]);
    }

    /**
     * Test clearing the hand.
     */
    public function testClear()
    {
        $hand = new CardHand();
        $hand->addCard(new CardGraphic("7", "♦"));
        $hand->clear();

        $this->assertEmpty($hand->getCards());
    }

    /**
     * Test value calculation for 21 points.
     */
    public function testGetTwentyOnePoints()
    {
        $hand = new CardHand();
        $hand->addCard(new CardGraphic("8", "♠"));
        $hand->addCard(new CardGraphic("K", "♥"));

        $value = $hand->getTwentyOneGameValue();
        $this->assertEquals(21, $value);
    }

    /**
     * Test getTwentyOneGameValue() with two Aces to see if points reduce.
     */
    public function testAcesReduceWhenOver21()
    {
        $hand = new CardHand();
        $hand->addCard(new CardGraphic("A", "♠"));
        $hand->addCard(new CardGraphic("A", "♥"));
        $hand->addCard(new CardGraphic("8", "♦"));

        $value = $hand->getTwentyOneGameValue();
        $this->assertEquals(10, $value);
    }

    /**
     * Test value calculation for Jack.
     */
    public function testValueJack()
    {
        $hand = new CardHand();
        $hand->addCard(new CardGraphic("J", "♠"));

        $value = $hand->getTwentyOneGameValue();
        $this->assertEquals(11, $value);
    }

    /**
     * Test value calculation for Queen.
     */
    public function testValueQueen()
    {
        $hand = new CardHand();
        $hand->addCard(new CardGraphic("Q", "♣"));

        $value = $hand->getTwentyOneGameValue();
        $this->assertEquals(12, $value);
    }
}
