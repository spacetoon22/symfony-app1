<?php

namespace App\Controller;

use App\Repository\DossierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\UserRepository;

class ClientController extends AbstractController
{
    #[Route('/clients', name: 'app_client')]
    public function index(Request $request, DossierRepository $repo): Response
    {
        $search  = trim($request->query->get('search', ''));
        $grouped = [];
        $total   = 0; // number of unique clients (emails)

        if ($search !== '') {
            $qb = $repo->createQueryBuilder('d')
                ->where(
                    'd.firstName LIKE :q
                    OR d.lastName LIKE :q
                    OR d.email    LIKE :q
                    OR d.phone    LIKE :q'
                )
                ->setParameter('q', '%' . $search . '%')
                ->orderBy('d.createdAt', 'DESC');

            // Non-admin: restrict to their own dossiers
            if (!$this->isGranted('ROLE_ADMIN')) {
                $qb->andWhere('d.assignedTo = :me')
                   ->setParameter('me', $this->getUser());
            }

            /** @var \App\Entity\Dossier[] $dossiers */
            $dossiers = $qb->getQuery()->getResult();

            // ── Group by email ──────────────────────────────────────────
            foreach ($dossiers as $d) {
                $key = strtolower(trim($d->getEmail() ?? 'unknown'));

                if (!isset($grouped[$key])) {
                    $grouped[$key] = [
                        'email'       => $d->getEmail(),
                        // Scalar fields: collect unique non-null values
                        'firstNames'  => [],
                        'lastNames'   => [],
                        'phones'      => [],
                        'cins'        => [],
                        'genders'     => [],
                        'dobs'        => [],
                        'cities'      => [],
                        'addresses'   => [],
                        // All insurance dossiers for this client
                        'dossiers'    => [],
                        // Latest createdAt across all dossiers
                        'latestDate'  => $d->getCreatedAt(),
                    ];
                }

                // Collect unique values for personal fields
                $this->collectUnique($grouped[$key]['firstNames'],  $d->getFirstName());
                $this->collectUnique($grouped[$key]['lastNames'],   $d->getLastName());
                $this->collectUnique($grouped[$key]['phones'],      $d->getPhone());
                $this->collectUnique($grouped[$key]['cins'],        $d->getCin());
                $this->collectUnique($grouped[$key]['genders'],     $d->getGender());
                $this->collectUnique($grouped[$key]['cities'],      $d->getCity());
                $this->collectUnique($grouped[$key]['addresses'],   $d->getAddress());

                // DOB: store as formatted string to deduplicate easily
                if ($d->getDob()) {
                    $formatted = $d->getDob()->format('Y-m-d');
                    if (!in_array($formatted, $grouped[$key]['dobs'])) {
                        $grouped[$key]['dobs'][] = $formatted;
                    }
                }

                // Track latest date
                if ($d->getCreatedAt() > $grouped[$key]['latestDate']) {
                    $grouped[$key]['latestDate'] = $d->getCreatedAt();
                }

                $grouped[$key]['dossiers'][] = $d;
            }

            $total = count($grouped);
        }

        return $this->render('client/index.html.twig', [
            'grouped' => $grouped,
            'total'   => $total,
            'search'  => $search,
        ]);
    }

    /**
     * Add $value to $array only if it's non-empty and not already present.
     */
    private function collectUnique(array &$array, ?string $value): void
    {
        $value = trim($value ?? '');
        if ($value !== '' && !in_array($value, $array)) {
            $array[] = $value;
        }
    }
    
    
    #[Route('/clients/by-commercial/{id}', name: 'app_client_by_commercial', methods: ['GET'])]
    public function byCommercial(
        int $id,
        DossierRepository $repo,
        \App\Repository\UserRepository $userRepo
    ): JsonResponse {
        // Admin only
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['error' => 'Forbidden'], 403);
        }
 
        $user = $userRepo->find($id);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }
 
        $dossiers = $repo->createQueryBuilder('d')
            ->where('d.assignedTo = :u')
            ->setParameter('u', $user)
            ->orderBy('d.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
 
        // Group by email (same logic as client index)
        $grouped = [];
        foreach ($dossiers as $d) {
            $key = strtolower(trim($d->getEmail() ?? 'unknown'));
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'name'      => $d->getFirstName() . ' ' . $d->getLastName(),
                    'email'     => $d->getEmail(),
                    'phone'     => $d->getPhone(),
                    'contracts' => [],
                ];
            }
            // Update name/phone to latest
            $grouped[$key]['name']  = $d->getFirstName() . ' ' . $d->getLastName();
            $grouped[$key]['phone'] = $d->getPhone() ?? $grouped[$key]['phone'];
 
            $grouped[$key]['contracts'][] = [
                'type'    => $d->getType(),
                'plan'    => $d->getPlan(),
                'status'  => $d->getStatus(),
                'premium' => $d->getPremium(),
                'start'   => $d->getStartDate()?->format('d M Y'),
                'end'     => $d->getEndDate()?->format('d M Y'),
                'created' => $d->getCreatedAt()?->format('d M Y'),
            ];
        }
 
        return new JsonResponse([
            'commercial' => $user->getUserIdentifier(),
            'clients'    => array_values($grouped),
            'total'      => count($grouped),
        ]);
    }
}