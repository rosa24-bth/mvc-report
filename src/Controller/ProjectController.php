<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for project .
 *
 * Handles the landing and about page.
 */
class ProjectController extends AbstractController
{
    /**
     * Route for the project landing page.
     *
     * @return Response Rendered HTML for /project.
     */
    #[Route('/project', name: 'project_index')]
    public function index(): Response
    {
        // Show the main page for the project section.
        return $this->render('project/index.html.twig');
    }

    /**
     * Route for the about page that describes the project.
     *
     * @return Response Rendered HTML for /project/about.
     */
    #[Route('/project/about', name: 'project_about')]
    public function about(): Response
    {
        // Show project background and summary.
        return $this->render('project/about.html.twig');
    }
}
