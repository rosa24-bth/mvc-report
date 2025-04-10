<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerJson extends AbstractController
{
    /*
    #[Route("/api/lucky/number")]
    public function jsonNumber(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'lucky-number' => $number,
            'lucky-message' => 'Hi there!',
        ];

        // return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    */

    #[Route("/api", name:"api_landing")]
    public function apiLanding(): Response
    {
        $html = '
            <!DOCTYPE html>
            <html>
                <head>
                    <meta charset="UTF-8">
                    <title>API Landing Page</title>
                    <style>
                        ul {
                            list-style: none;
                            padding: 0;
                        }
                        li {
                            margin-bottom: 0.5rem;
                        }
                    </style>
                </head>
                <body>
                    <h1>JSON API offerings</h1>
                    <ul>
                        <li><a href="/api/quote">/api/quote</a></li>
                    </ul>
                </body>
            </html>';
        return new Response($html);
    }

    #[Route("/api/quote", name:"api_quote")]
    public function apiQuote(): JsonResponse
    {
        $quotes = [
            "After snow comes slask",
            "A truth that's told with bad intent beats all the lies you can invent",
            "Adventure is just bad planning",
        ];

        $quote = $quotes[array_rand($quotes)];

        $qouteData = [
            'quote' => $quote,
            'timestamp' => date("Y-m-d H:i:s"),
        ];

        $response = new JsonResponse($qouteData);
        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);
        return $response;
    }
}
