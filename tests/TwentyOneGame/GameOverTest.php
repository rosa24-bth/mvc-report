<?php

namespace App\TwentyOneGame;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class GameOVer.
 */
class GameOverTest extends TestCase
{
    /**
     * Player busts so bank wins.
     */
    public function testPlayerBusts()
    {
        $result = GameOver::determineWinner(22, 19);
        $this->assertEquals("Banken vinner", $result);
    }

    /**
     * Bank busts so player wins.
     */
    public function testBankBusts()
    {
        $result = GameOver::determineWinner(19, 22);
        $this->assertEquals("Spelaren vinner", $result);
    }

    /**
     * Points are equal so bank wins.
     */
    public function testBankWinsOnEqualScore()
    {
        $result = GameOver::determineWinner(19, 19);
        $this->assertEquals("Banken vinner", $result);
    }

    /**
     * Player has higher score so player wins.
     */
    public function testPlayerWinsOnHigherScore()
    {
        $result = GameOver::determineWinner(20, 18);
        $this->assertEquals("Spelaren vinner", $result);
    }
}
