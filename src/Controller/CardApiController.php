<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\DeckOfCards;

class CardApiController extends AbstractController
{
    #[Route("/api", name: "api_index")]
    public function index(): Response
    {
        $routes = [
            'GET /api/deck' => 'Här får du en sorterad kortlek.',
            'POST /api/deck/shuffle' => 'Blandar kortleken och returnerar den.',
            'POST /api/deck/draw' => 'Drar ett kort från kortleken.',
            'POST /api/deck/draw/:number' => 'Drar flera kort från kortleken.',
            'GET /api/game' => 'Här får du den aktuella ställningen i spelet.',
        ];

        return $this->render('card/api.html.twig', ['routes' => $routes]);
    }

    #[Route("/api/deck", name: "api_deck", methods: ["GET"])]
    public function apiDeck(SessionInterface $session): JsonResponse
    {
        $deck = new DeckOfCards();
        $session->set("card_deck", $deck);

        return $this->json([
            "cards" => $deck->getCardsAsString(),
            "count" => count($deck->getCards())
        ]);
    }

    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ["POST"])]
    public function apiShuffle(SessionInterface $session): JsonResponse
    {
        $deck = new DeckOfCards();
        $deck->shuffle();

        $session->set("card_deck", $deck);

        return $this->json([
            "cards" => $deck->getCardsAsString(),
            "count" => count($deck->getCards())
        ]);
    }

    #[Route("/api/deck/draw", name: "api_deck_draw", methods: ["POST"])]
    public function apiDraw(SessionInterface $session): JsonResponse
    {
        $deck = $session->get("card_deck");

        if (!$deck) {
            $deck = new DeckOfCards();
        }

        $card = $deck->drawCard();
        $session->set("card_deck", $deck);

        return $this->json([
            "drawn" => (string) $card,
            "remaining" => count($deck->getCards())
        ]);
    }

    #[Route("/api/deck/draw/{number<\d+>}", name: "api_deck_draw_number", methods: ["POST"])]
    public function apiDrawNumber(int $number, SessionInterface $session): JsonResponse
    {
        $deck = $session->get("card_deck");

        if (!$deck) {
            $deck = new DeckOfCards();
        }

        $cards = $deck->drawCards($number);
        $session->set("card_deck", $deck);

        $cardStrings = [];

        foreach ($cards as $card) {
            $cardStrings[] = (string) $card;
        }

        return $this->json([
            "drawn" => $cardStrings,
            "remaining" => count($deck->getCards())
        ]);
    }

    #[Route("/api/game", name: "api_game", methods: ["GET"])]
    public function apiGame(SessionInterface $session): JsonResponse
    {
        $game = $session->get("game");
        $bank = $session->get("bank");
        $result = $session->get("result");

        if (!$game) {
            return $this->json([
                "error" => "Spelet har inte startats ännu."
            ]);
        }

        // Get player hand.
        $playerHand = $game->getPlayerHand()->getCards();
        $playerCards = [];
        foreach ($playerHand as $card) {
            $playerCards[] = (string) $card;
        }
        $playerScore = $game->getPlayerScore();

        $response = [
            "player" => [
                "cards" => $playerCards,
                "score" => $playerScore
            ]
        ];

        // If game is over then show bank aswell.
        if ($bank && $result) {
            $bankHand = $bank->getHand()->getCards();
            $bankCards = [];
            foreach ($bankHand as $card) {
                $bankCards[] = (string) $card;
            }

            $response["bank"] = [
                "cards" => $bankCards,
                "score" => $bank->getScore()
            ];

            $response["result"] = $result;
        }

        return $this->json($response);
    }
}
