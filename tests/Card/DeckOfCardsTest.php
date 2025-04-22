<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for the DeckOfCards class.
 */
class DeckOfCardsTest extends TestCase
{
    /**
     * Test that deck has 52 cards.
     */
    public function testDeckHas52Cards()
    {
        $deck = new DeckOfCards();
        $cards = $deck->getCards();

        $this->assertCount(52, $cards);
    }

    /**
     * Test that shuffle changes order of the cards.
     */
    public function testShufflingWorks()
    {
        $deck = new DeckOfCards();
        $before = $deck->getCardsAsString();

        $deck->shuffle();
        $after = $deck->getCardsAsString();

        $this->assertNotEquals($before, $after);
    }

    /**
     * Test drawing a single card from deck.
     */
    public function testDrawSingleCard()
    {
        $deck = new DeckOfCards();
        $card = $deck->drawCard();

        $this->assertInstanceOf("App\Card\CardGraphic", $card);
        $this->assertCount(51, $deck->getCards());
    }

    /**
     * Test drawing multiple cards from deck.
     */
    public function testDrawMultipleCards()
    {
        $deck = new DeckOfCards();
        $cards = $deck->drawCards(7);

        $this->assertCount(7, $cards);
        foreach ($cards as $card) {
            $this->assertInstanceOf("App\Card\CardGraphic", $card);
        }
        $this->assertCount(45, $deck->getCards());
    }
}
