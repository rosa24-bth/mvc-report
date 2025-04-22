<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateDice()
    {
        $die = new Dice();
        $this->assertInstanceOf("\App\Dice\Dice", $die);

        $res = $die->getAsString();
        $this->assertNotEmpty($res);
    }

    /**
     * Roll the dice and assert that the value is between 1 and 6.
     */
    public function testRoll()
    {
        $die = new Dice();
        $value = $die->roll();
        $this->assertIsInt($value);
        $this->assertGreaterThanOrEqual(1, $value);
        $this->assertLessThanOrEqual(6, $value);
    }

    /**
     * Roll the dice and verify that getValue() returns the same as roll().
     */
    public function testGetValue()
    {
        $die = new Dice();
        $rolled = $die->roll();
        $this->assertEquals($rolled, $die->getValue());
    }

    /**
     * Roll the dice and verify that getAsString() returns a string with the value.
     */
    public function testGetAsString()
    {
        $die = new Dice();
        $rolled = $die->roll();
        $expected = "[{$rolled}]";
        $this->assertEquals($expected, $die->getAsString());
    }
}
