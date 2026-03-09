<?php

namespace App\Controller;

use App\Repository\DossierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(DossierRepository $dossierRepository): Response
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

        $totalPremium = array_sum(
            array_map(fn($d) => $d->getPremium() ?? 0, $approvedDossiers)
        );

        // Group premiums by commercial — use username if set, fallback to email
        $premiumByCommercial = [];
        foreach ($approvedDossiers as $d) {
            $commercial = $d->getAssignedTo();
            if (!$commercial) continue;

            $name = $commercial->getDisplayName(); // ✅ username or email fallback
            $premiumByCommercial[$name] = ($premiumByCommercial[$name] ?? 0) + ($d->getPremium() ?? 0);
        }

        arsort($premiumByCommercial);

        return $this->render('dashboard/index.html.twig', [
            'counts'               => $counts,
            'totalPremium'         => $totalPremium,
            'premiumByCommercial'  => $premiumByCommercial,
        ]);
    }
}