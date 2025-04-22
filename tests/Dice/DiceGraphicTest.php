<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DiceGraphic.
 */
class DiceGraphicTest extends TestCase
{
    /**
     * Test that DiceGraphic is a subclass of Dice.
     */
    public function testIsSubclassOfDice()
    {
        $die = new DiceGraphic();
        $this->assertInstanceOf("App\Dice\DiceGraphic", $die);
    }

    /**
     * Test if getAsString() returns a dice symbol.
     */
    public function testGetAsStringReturnsDiceSymbol()
    {
        $die = new DiceGraphic();
        $die->roll();
        $symbol = $die->getAsString();

        $this->assertIsString($symbol);
        $this->assertStringContainsString($symbol, "⚀⚁⚂⚃⚄⚅");
    }
}
