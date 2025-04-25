<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardApiController extends AbstractController
{
    #[Route("/api", name: "api_index")]
    public function index(): Response
    {
        $routes = [
            [
                'method' => 'GET',
                'path' => '/api/deck',
                'description' => 'Här får du en sorterad kortlek.',
            ],
            [
                'method' => 'POST',
                'path' => '/api/deck/shuffle',
                'description' => 'Blandar kortleken och returnerar den.',
            ],
            [
                'method' => 'GET',
                'path' => '/api/deck/draw',
                'description' => 'Drar ett kort från kortleken.',
            ],
            [
                'method' => 'POST',
                'path' => '/api/deck/draw/:number',
                'description' => 'Drar flera kort från kortleken.',
            ],
            [
                'method' => 'GET',
                'path' => '/api/game',
                'description' => 'Här får du den aktuella ställningen i spelet.',
            ],
            [
                'method' => 'GET',
                'path' => '/api/library/books',
                'description' => 'Visar alla böcker från biblioteket.',
            ],
            [
                'method' => 'GET',
                'path' => '/api/library/book/{isbn}',
                'description' => 'Visar en bok genom ISBN (testa t.ex. /api/library/book/123456789).',
            ],
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

    #[Route("/api/deck/draw", name: "api_deck_draw_get", methods: ["GET"])]
    public function apiDrawGet(SessionInterface $session): JsonResponse
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

    #[Route("/api/library/books", name: "api_library_books", methods: ["GET"])]
    public function apiLibraryBooks(
        BookRepository $bookRepository
    ): JsonResponse {
        $books = $bookRepository->findAll();
        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/library/book/{isbn}", name: "api_library_book", methods: ["GET"])]
    public function apiLibraryBook(
        string $isbn,
        BookRepository $bookRepository
    ): JsonResponse {
        $book = $bookRepository->findOneBy(['isbn' => $isbn]);

        if (!$book) {
            return $this->json([
                "error" => "Det finns tyvärr ingen bok med ISBN $isbn"
            ], 404);
        }

        $response = $this->json($book);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
