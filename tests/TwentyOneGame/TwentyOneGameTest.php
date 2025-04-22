<?php

namespace App\TwentyOneGame;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class TwentyOneGame.
 */
class TwentyOneGameTest extends TestCase
{
    /**
     * Create game and verify it has a deck and a player hand.
     */
    public function testCreateGame()
    {
        $game = new TwentyOneGame();
        $this->assertInstanceOf("App\Card\DeckOfCards", $game->getCurrentDeck());
        $this->assertInstanceOf("App\Card\CardHand", $game->getPlayerHand());
    }

    /**
     * Start game and verify player gets two cards.
     */
    public function testStartGameGivesTwoCards()
    {
        $game = new TwentyOneGame();
        $game->startGame();
        $this->assertCount(2, $game->getPlayerHand()->getCards());
    }

    /**
     * Draw one card and check that card count increases.
     */
    public function testDrawCardIncreasesCardCount()
    {
        $game = new TwentyOneGame();
        $game->startGame();
        $before = count($game->getPlayerHand()->getCards());
        $game->drawCard();
        $after = count($game->getPlayerHand()->getCards());
        $this->assertEquals($before + 1, $after);
    }

    /**
     * Check that getPlayerScore() returns a reasonable value.
     */
    public function testPlayerScoreIsWithinExpectedRange()
    {
        $game = new TwentyOneGame();
        $game->startGame();
        $score = $game->getPlayerScore();
        $this->assertGreaterThanOrEqual(0, $score);
        $this->assertLessThanOrEqual(100, $score);
    }

    /**
     * Draw cards and control so that teh deck size shrinks.
     */
    public function testDeckShrinksWhenDrawingCards()
    {
        $game = new TwentyOneGame();
        $initial = $game->getNumOfRemainingCards();
        $game->startGame();
        $after = $game->getNumOfRemainingCards();
        $this->assertLessThan($initial, $after);
    }
}
