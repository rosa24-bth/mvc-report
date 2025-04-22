<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test case for class CardGraphic.
 */
class CardGraphicTest extends TestCase
{
    public function testToStringReturnsString()
    {
        $card = new CardGraphic("8", "♠");
        $expected = $card->getAsString();
        $this->assertEquals($expected, (string)$card);
    }
}
