<?php

namespace App\Controller;

use App\TwentyOneGame\TwentyOneGame;
use App\TwentyOneGame\Bank;
use App\TwentyOneGame\GameOver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class TwentyOneController extends AbstractController
{
    #[Route("/game", name: "game_home")]
    public function gameHome(): Response
    {
        return $this->render('21_game/index.html.twig');
    }

    #[Route("/game/doc", name: "game_doc")]
    public function gameDoc(): Response
    {
        return $this->render('21_game/doc.html.twig');
    }

    #[Route("/game/play", name: "game_play", methods: ["GET"])]
    public function gamePlay(SessionInterface $session): Response
    {
        $game = $session->get("game");

        if (!$game) {
            $game = new TwentyOneGame();
            $game->startGame();
            $session->set("game", $game);
        }

        $playerHand = $game->getPlayerHand()->getCards();
        $playerScore = $game->getPlayerHand()->getTwentyOneGameValue();
        $remaining = $game->getNumOfRemainingCards();

        $gameOver = false;
        $result = null;

        if ($playerScore > 21) {
            $gameOver = true;
            $result = "Banken vinner!";
        }

        return $this->render('21_game/play.html.twig', [
            "playerHand" => $playerHand,
            "playerScore" => $playerScore,
            "remaining" => $remaining,
            "gameOver" => $gameOver,
            "result" => $result
        ]);
    }

    #[Route("/game/draw", name: "game_draw", methods: ["POST"])]
    public function gameDraw(SessionInterface $session): Response
    {
        /** @var TwentyOneGame $game */
        $game = $session->get("game");
        $game->drawCard();

        $session->set("game", $game);

        return $this->redirectToRoute("game_play");
    }

    #[Route("/game/stay", name: "game_stay", methods: ["POST"])]
    public function gameStay(SessionInterface $session): Response
    {
        /** @var TwentyOneGame $game */
        $game = $session->get("game");

        $bank = new Bank();
        $bank->playTurn($game->getCurrentDeck());

        $gameOver = new GameOver();
        $result = $gameOver->determineWinner(
            $game->getPlayerScore(),
            $bank->getScore()
        );

        return $this->render('21_game/play.html.twig', [
            "playerHand" => $game->getPlayerHand()->getCards(),
            "playerScore" => $game->getPlayerScore(),
            "bankHand" => $bank->getHand()->getCards(),
            "bankScore" => $bank->getScore(),
            "remaining" => $game->getNumOfRemainingCards(),
            "gameOver" => true,
            "result" => $result
        ]);
    }

    #[Route("/game/reset", name: "game_reset", methods: ["POST"])]
    public function gameReset(SessionInterface $session): Response
    {
        $session->remove("game");

        return $this->redirectToRoute("game_play");
    }
}
