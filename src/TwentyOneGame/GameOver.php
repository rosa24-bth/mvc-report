<?php

namespace App\TwentyOneGame;

class GameOver
{
    public static function determineWinner(int $playerScore, int $bankScore): string
    {
        if ($playerScore > 21) {
            return "Banken vinner";
        }

        if ($bankScore > 21) {
            return "Spelaren vinner";
        }

        if ($bankScore >= $playerScore) {
            return "Banken vinner";
        }

        return "Spelaren vinner";
    }
}
