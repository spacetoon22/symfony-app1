<?php
namespace App\Controller;

use App\Repository\DossierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(DossierRepository $dossierRepository, Request $request): Response
    {
        $allDossiers = $dossierRepository->findAll();

        $counts = [
            'total'    => count($allDossiers),
            'pending'  => count(array_filter($allDossiers, fn($d) => $d->getStatus() === 'pending')),
            'claimed'  => count(array_filter($allDossiers, fn($d) => $d->getStatus() === 'claimed')),
            'review'   => count(array_filter($allDossiers, fn($d) => $d->getStatus() === 'review')),
            'approved' => count(array_filter($allDossiers, fn($d) => $d->getStatus() === 'approved')),
            'rejected' => count(array_filter($allDossiers, fn($d) => $d->getStatus() === 'rejected')),
        ];

        $approvedDossiers = array_filter($allDossiers, fn($d) => $d->getStatus() === 'approved');

        // ── Selected month filter (default = current month) ──
        $selectedMonth = (int) $request->query->get('month', (int) date('n'));
        $selectedYear  = (int) $request->query->get('year',  (int) date('Y'));

        // ── Filter approved dossiers by selected month ──
        $filteredDossiers = array_filter($approvedDossiers, function ($d) use ($selectedMonth, $selectedYear) {
            $date = $d->getCreatedAt();
            if (!$date) return false;
            return (int)$date->format('n') === $selectedMonth
                && (int)$date->format('Y') === $selectedYear;
        });

        // ── Revenue per commercial (filtered) ──
        $premiumByCommercial = [];
        foreach ($filteredDossiers as $d) {
            $commercial = $d->getAssignedTo();
            if (!$commercial) continue;
            $name = $commercial->getDisplayName();
            // Total for dossier = monthly premium × duration months
            $months = $d->getDurationMonths() ?? 1;
            $total  = ($d->getPremium() ?? 0) * $months;
            $premiumByCommercial[$name] = ($premiumByCommercial[$name] ?? 0) + $total;
        }
        arsort($premiumByCommercial);

        $totalPremium = array_sum($premiumByCommercial);

        // ── Chart data: revenue per commercial for chart.js ──
        $chartLabels = array_keys($premiumByCommercial);
        $chartData   = array_values($premiumByCommercial);

        // ── Monthly totals for the year (for the line overview) ──
        $monthlyTotals = array_fill(1, 12, 0);
        foreach ($approvedDossiers as $d) {
            $date = $d->getCreatedAt();
            if (!$date || (int)$date->format('Y') !== $selectedYear) continue;
            $m      = (int)$date->format('n');
            $months = $d->getDurationMonths() ?? 1;
            $monthlyTotals[$m] += ($d->getPremium() ?? 0) * $months;
        }

        // ── Available months that have data (for dropdown) ──
        $availableMonths = [];
        foreach ($approvedDossiers as $d) {
            $date = $d->getCreatedAt();
            if (!$date) continue;
            $key = $date->format('Y-n');
            if (!isset($availableMonths[$key])) {
                $availableMonths[$key] = [
                    'year'  => (int)$date->format('Y'),
                    'month' => (int)$date->format('n'),
                    'label' => $date->format('F Y'),
                ];
            }
        }
        // Always include current month even if empty
        $currentKey = date('Y-n');
        if (!isset($availableMonths[$currentKey])) {
            $availableMonths[$currentKey] = [
                'year'  => (int)date('Y'),
                'month' => (int)date('n'),
                'label' => date('F Y'),
            ];
        }
        krsort($availableMonths);

        $monthNames = ['', 'January','February','March','April','May','June',
                       'July','August','September','October','November','December'];

        return $this->render('dashboard/index.html.twig', [
            'counts'              => $counts,
            'totalPremium'        => $totalPremium,
            'premiumByCommercial' => $premiumByCommercial,
            'chartLabels'         => json_encode(array_values($chartLabels)),
            'chartData'           => json_encode(array_values($chartData)),
            'monthlyTotals'       => json_encode(array_values($monthlyTotals)),
            'selectedMonth'       => $selectedMonth,
            'selectedYear'        => $selectedYear,
            'selectedMonthName'   => $monthNames[$selectedMonth] ?? '',
            'availableMonths'     => $availableMonths,
        ]);
    }
}