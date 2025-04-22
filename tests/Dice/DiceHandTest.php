<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DiceHand.
 */
class DiceHandTest extends TestCase
{
    /**
     * Create a DiceHand object and check if it is empty.
     */
    public function testCreateDiceHand()
    {
        $hand = new DiceHand();
        $this->assertInstanceOf("App\Dice\DiceHand", $hand);
        $this->assertEquals(0, $hand->getNumberDices());
    }
    /**
     * Add dice to hand and verify number of dices.
     */
    public function testAddDice()
    {
        $hand = new DiceHand();
        $hand->add(new Dice());
        $hand->add(new Dice());
        $this->assertEquals(2, $hand->getNumberDices());
    }

    /**
     * Roll dice and verify that values are intgers.
     */
    public function testRollAndGetValues()
    {
        $hand = new DiceHand();
        $hand->add(new Dice());
        $hand->add(new Dice());
        $hand->roll();
        $values = $hand->getValues();

        $this->assertCount(2, $values);
        foreach ($values as $value) {
            $this->assertIsInt($value);
            $this->assertGreaterThanOrEqual(1, $value);
            $this->assertLessThanOrEqual(6, $value);
        }
    }

    /**
     * Test that getString() returns an array of strings after rolling the dice.
     */
    public function testGetString()
    {
        $hand = new DiceHand();
        $hand->add(new Dice());
        $hand->roll();
        $strings = $hand->getString();

        $this->assertIsArray($strings);
        $this->assertIsString($strings[0]);
    }
}
