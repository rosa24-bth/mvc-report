<?php

namespace App\Controller;

use App\Repository\LowEconomicStandardRepository;
use App\Repository\LongtermEconomicSupportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for the JSON API with GET routes.
 *
 * Also has POST route to fetch statistics for different population groups.
 */
class ProjectApiController extends AbstractController
{
    /**
     * Get all rows for "Samtliga personer" from the LowEconomicStandard table.
     */
    #[Route('/api/low/group/samtliga', name: 'api_low_samtliga')]
    public function lowSamtliga(LowEconomicStandardRepository $repo): JsonResponse
    {
        $data = $repo->findBy(['groupName' => 'Samtliga personer']);
        return $this->json($data);
    }

    /**
     * Get all rows for "Ensamstående kvinnor utan barn" from the LowEconomicStandard table.
     */
    #[Route('/api/low/group/ensamstaende-kvinnor-utan-barn', name: 'api_low_ensamstaende_kvinnor_utan_barn')]
    public function lowWomenNoChildren(LowEconomicStandardRepository $repo): JsonResponse
    {
        $data = $repo->findBy(['groupName' => 'Ensamstående kvinnor utan barn']);
        return $this->json($data);
    }

    /**
     * Get all rows for "Samtliga personer" from the LongtermEconomicSupport table.
     */
    #[Route('/api/long/group/samtliga', name: 'api_long_samtliga')]
    public function longSamtliga(LongtermEconomicSupportRepository $repo): JsonResponse
    {
        $data = $repo->findBy(['groupName' => 'Samtliga personer']);
        return $this->json($data);
    }

    /**
     * Get all rows for "Ej boende med förälder" from the LongtermEconomicSupport table.
     */
    #[Route('/api/long/group/ej-boende-med-foralder', name: 'api_long_ej_boende')]
    public function longEjBoende(LongtermEconomicSupportRepository $repo): JsonResponse
    {
        $data = $repo->findBy(['groupName' => 'Ej boende med förälder']);
        return $this->json($data);
    }

    /**
     * Get all rows for "Barn till ensamstående kvinnor" from the LongtermEconomicSupport table.
     */
    #[Route('/api/long/group/barn-till-ensamstaende-kvinnor', name: 'api_long_barn_ensamstaende')]
    public function longChildrenOfSingleWomen(LongtermEconomicSupportRepository $repo): JsonResponse
    {
        $data = $repo->findBy(['groupName' => 'Barn till ensamstående kvinnor']);
        return $this->json($data);
    }

    /**
     * Get rows from LowEconomicStandard using a group name sent via POST.
     *
     * @param Request $request The HTTP request, expects JSON with 'groupName'.
     * @param LowEconomicStandardRepository $repo The repository for DB access.
     */
    #[Route('/api/low/group', name: 'api_low_post', methods: ['POST'])]
    public function lowByGroupPost(Request $request, LowEconomicStandardRepository $repo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $groupName = $data['groupName'] ?? null;

        if (!$groupName) {
            return $this->json(['error' => 'Ingen grupp angiven.'], 400);
        }

        $result = $repo->findBy(['groupName' => $groupName]);

        if (!$result) {
            return $this->json(['error' => 'Ingen data hittades för angiven grupp.'], 404);
        }

        return $this->json($result);
    }

    /**
     * Return all unique group names available in the LowEconomicStandard table for user experience smoothness.
     */
    #[Route('/api/low/groups', name: 'api_low_all_groups')]
    public function lowAllGroups(LowEconomicStandardRepository $repo): JsonResponse
    {
        $allData = $repo->findAll();

        // Extract unique group names using getGroupName().
        $groupNames = array_unique(array_map(fn($row) => $row->getGroupName(), $allData));

        // Sort alphabetically for easier reading.
        sort($groupNames);

        return $this->json($groupNames);
    }
}
