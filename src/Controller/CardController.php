<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\DeckOfCards;

class CardController extends AbstractController
{
    #[Route("/card", name: "card_index")]
    public function index(): Response
    {
        return $this->render('card/index.html.twig');
    }

    #[Route("/card/session", name: "card_session")]
    public function session(SessionInterface $session): Response
    {
        $sessionData = $session->all();

        $data = [
            'session' => $sessionData
        ];

        return $this->render('card/session.html.twig', $data);
    }

    #[Route("/card/session/delete", name: "card_session_delete")]
    public function sessionDelete(SessionInterface $session): Response
    {
        $session->clear();

        $this->addFlash(
            'notice',
            'Sessionen är nu raderad.'
        );

        return $this->redirectToRoute('card_session');
    }

    #[Route("/card/deck", name: "card_deck")]
    public function deck(SessionInterface $session): Response
    {
        $deck = new \App\Card\DeckOfCards();

        $session->set("card_deck", $deck);

        $data = [
            "cards" => $deck->getCards()
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "card_deck_shuffle")]
    public function shuffle(SessionInterface $session): Response
    {
        $deck = new \App\Card\DeckOfCards();
        $deck->shuffle();

        $session->set("card_deck", $deck);

        $data = [
            "cards" => $deck->getCards()
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "card_deck_draw")]
    public function draw(SessionInterface $session): Response
    {
        $deck = $session->get("card_deck");

        if (!$deck) {
            $deck = new \App\Card\DeckOfCards();
        }

        $card = $deck->drawCard();

        $session->set("card_deck", $deck);

        $data = [
            "card" => $card,
            "remaining" => count($deck->getCards())
        ];

        return $this->render('card/draw.html.twig', $data);
    }

    #[Route("/card/deck/draw/{number<\d+>}", name: "card_deck_draw_number")]
    public function drawNumber(int $number, SessionInterface $session): Response
    {
        $deck = $session->get("card_deck");

        if (!$deck) {
            $deck = new DeckOfCards();
        }

        $cards = $deck->drawCards($number);

        $session->set("card_deck", $deck);

        $data = [
            "cards" => $cards,
            "remaining" => count($deck->getCards())
        ];

        return $this->render('card/draw_number.html.twig', $data);
    }
}
