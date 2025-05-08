<?php

namespace App\Controller;

use App\Repository\LowEconomicStandardRepository;
use App\Repository\LongtermEconomicSupportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for sustainability views and data presentation.
 *
 * Handles table view, graphs and API page.
 */
class SustainabilityController extends AbstractController
{
    /**
     * Shows a table view of both economic indicators.
     *
     * @param LowEconomicStandardRepository $lowRepo Repository for low economic standard data.
     * @param LongtermEconomicSupportRepository $longRepo Repository for long-term support data.
     * @return Response Rendered HTML with table data.
     */
    #[Route('/sustainability', name: 'sustainability_index')]
    public function index(
        LowEconomicStandardRepository $lowRepo,
        LongtermEconomicSupportRepository $longRepo
    ): Response {
        // Fetch all rows from both indicators.
        $lowData = $lowRepo->findAll();
        $longData = $longRepo->findAll();

        // Go through both datasets (low + long) and collect all years. array_map extracts the year from each row.
        // array_merge combines the two lists of years into one big list. array_unique removes any duplicates
        // so each year only appears once. sort makes sure the years are in the right order.
        $years = array_unique(array_merge(
            array_map(fn ($row) => $row->getYear(), $lowData),
            array_map(fn ($row) => $row->getYear(), $longData)
        ));
        sort($years);

        // Turn the list into a "pivot" array where each group has values per year because this will make things
        // easier to build a table like [group][year] => value in the Twig template.
        $lowPivot = [];
        foreach ($lowData as $row) {
            $lowPivot[$row->getGroupName()][$row->getYear()] = $row->getValue();
        }

        // Same thing for the longterm support. This makes it easier to show the data in a table later.
        $longPivot = [];
        foreach ($longData as $row) {
            $longPivot[$row->getGroupName()][$row->getYear()] = $row->getValue();
        }

        // Send all to Twig.
        return $this->render('project/sustainability.html.twig', [
            'years' => $years,
            'low_pivot' => $lowPivot,
            'long_pivot' => $longPivot,
        ]);
    }

    /**
     * Shows interactive graphs for both indicators, also add group selector.
     *
     * @param Request $request HTTP request for user-selected groups.
     * @param LowEconomicStandardRepository $lowRepo Repository for low economic standard data.
     * @param LongtermEconomicSupportRepository $longRepo Repository for long-term support data.
     * @return Response Rendered HTML with charts.
     */
    #[Route('/sustainability/graphs', name: 'sustainability_graphs')]
    public function graphs(
        Request $request,
        LowEconomicStandardRepository $lowRepo,
        LongtermEconomicSupportRepository $longRepo
    ): Response {
        // Fetch all data for both indicators.
        $lowData = $lowRepo->findAll();
        $longData = $longRepo->findAll();

        // Build sorted list of available groups for low economic data.
        $lowGroups = array_unique(array_map(fn ($row) => $row->getGroupName(), $lowData));
        sort($lowGroups);
        $selectedGroup1 = $request->query->get('group1') ?? 'Samtliga personer';

        // Filter and sort rows for selected low group.
        $lowFiltered = array_filter($lowData, fn ($row) => $row->getGroupName() === $selectedGroup1);
        usort($lowFiltered, fn ($firstRow, $secondRow) => $firstRow->getYear() <=> $secondRow->getYear());
        $chartLabels = array_map(fn ($row) => $row->getYear(), $lowFiltered);
        $chartValues = array_map(fn ($row) => $row->getValue(), $lowFiltered);

        // Apply min/max range for the chart Y-axis.
        $lowMin = min($chartValues);
        $lowMax = max($chartValues);
        $lowSuggestedMin = floor($lowMin - 1);
        $lowSuggestedMax = ceil($lowMax + 1);

        // Build sorted list of available groups for long-term support data.
        $longGroups = array_unique(array_map(fn ($row) => $row->getGroupName(), $longData));
        sort($longGroups);
        $selectedGroup2 = $request->query->get('group2') ?? 'Samtliga personer';

        $longFiltered = array_filter($longData, fn ($row) => $row->getGroupName() === $selectedGroup2);
        usort($longFiltered, fn ($rowA, $rowB) => $rowA->getYear() <=> $rowB->getYear());
        $longLabels = array_map(fn ($row) => $row->getYear(), $longFiltered);
        $longValues = array_map(fn ($row) => $row->getValue(), $longFiltered);

        $longMin = min($longValues);
        $longMax = max($longValues);
        $longSuggestedMin = floor($longMin - 0.5);
        $longSuggestedMax = ceil($longMax + 0.5);

        // Render chart view with all data passed in.
        return $this->render('project/graphs.html.twig', [
            'chart_labels' => $chartLabels,
            'chart_values' => $chartValues,
            'selected_group1' => $selectedGroup1,
            'low_groups' => $lowGroups,
            'long_labels' => $longLabels,
            'long_values' => $longValues,
            'selected_group2' => $selectedGroup2,
            'long_groups' => $longGroups,
            'low_suggested_min' => $lowSuggestedMin,
            'low_suggested_max' => $lowSuggestedMax,
            'long_suggested_min' => $longSuggestedMin,
            'long_suggested_max' => $longSuggestedMax,
        ]);
    }

    /**
     * Shows the API documentation page.
     *
     * @return Response Rendered HTML for /proj/api.
     */
    #[Route('/proj/api', name: 'project_api')]
    public function api(): Response
    {
        return $this->render('project/api.html.twig');
    }
}
