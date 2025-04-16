<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route("/game/play", name: "game_play")]
    public function gamePlay(): Response
    {
        return $this->render('21_game/play.html.twig');
    }
}
