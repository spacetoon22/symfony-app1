<?php

namespace App\Controller;

use App\Entity\Dossier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiController extends AbstractController
{
    #[Route('/api/create-document', name: 'api_create_document', methods: ['POST'])]
    public function createDocument(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json([
                'error' => 'Invalid JSON'
            ], 400);
        }

        $dossier = new Dossier();

        $dossier->setFirstName($data['firstName'] ?? 'Unknown');
        $dossier->setLastName($data['lastName'] ?? 'Unknown');
        $dossier->setEmail($data['email'] ?? 'test@email.com');

        $dossier->setPhone($data['phone'] ?? null);
        $dossier->setCity($data['city'] ?? null);
        $dossier->setPlan($data['plan'] ?? null);

        $dossier->setType($data['type'] ?? 'health');

        $em->persist($dossier);
        $em->flush();

        return $this->json([
            'status' => 'created',
            'id' => $dossier->getId()
        ]);
    }
}
