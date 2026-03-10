<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Dossier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

final class TaxiController extends AbstractController
{
    // ── Step 1: Show & submit form ──
    #[Route('/taxi', name: 'app_taxi')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $dossier = new Dossier();

            $dossier->setFirstName($request->request->get('first_name', ''));
            $dossier->setLastName($request->request->get('last_name', ''));
            $dossier->setEmail($request->request->get('email', ''));
            $dossier->setPhone($request->request->get('phone', ''));
            $dossier->setCin($request->request->get('cin'));
            $dossier->setGender($request->request->get('gender'));
            $dossier->setCity($request->request->get('city'));
            $dossier->setAddress($request->request->get('address'));
            $dossier->setPlan($request->request->get('plan'));
            $dossier->setConditions($request->request->get('conditions'));
            $dossier->setMedicalNotes($request->request->get('medical_notes'));
            $dossier->setCommercialNotes($request->request->get('commercial_notes'));
            $dossier->setDocuments($request->request->all('docs') ?? []);
            $dossier->setType('taxi');
            $dossier->setPaymentMethod($request->request->get('payment_method', 'card'));

            // Assign commercial
            $commercialEmail = $request->request->get('commercial_email', '');
            $commercial = $em->getRepository(User::class)->findOneBy(['email' => $commercialEmail]);
            if (!$commercial) {
                $this->addFlash('error', 'No commercial found with email: ' . $commercialEmail);
                return $this->render('taxi/index.html.twig');
            }
            $dossier->setAssignedTo($commercial);
            $dossier->setClaimedBy($commercial);
            $dossier->setStatus('claimed');

            // Duration months (3, 6, or 12)
            $durationMonths = (int) $request->request->get('duration_months', 12);
            $dossier->setDurationMonths($durationMonths);

            // Fixed prices: 3→300, 6→600, 12→1200 MAD total
            // Store monthly rate = total / months
            $fixedPrices = [3 => 300, 6 => 600, 12 => 1200];
            $totalPrice  = $fixedPrices[$durationMonths] ?? ($durationMonths * 100);
            $dossier->setPremium($totalPrice / $durationMonths); // monthly rate

            // Date of birth
            $dob = $request->request->get('dob');
            if ($dob) {
                $dossier->setDob(new \DateTime($dob));
            }

            // Start date + compute end date
            $startDate = $request->request->get('start_date');
            if ($startDate) {
                $start = new \DateTime($startDate);
                $dossier->setStartDate($start);
                $end = (clone $start)->modify('+' . $durationMonths . ' months')->modify('-1 day');
                $dossier->setEndDate($end);
            }

            // File uploads
            $uploadedFiles = $request->files->get('files');
            $savedFilenames = [];
            if ($uploadedFiles) {
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/dossiers';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);
                foreach ($uploadedFiles as $file) {
                    if ($file && $file->isValid()) {
                        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $extension    = $file->guessExtension() ?? $file->getClientOriginalExtension();
                        $safeFilename = time() . '_' . bin2hex(random_bytes(4)) . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName) . '.' . $extension;
                        $file->move($uploadDir, $safeFilename);
                        $savedFilenames[] = $safeFilename;
                    }
                }
            }
            $dossier->setUploadedFiles($savedFilenames);

            $em->persist($dossier);
            $em->flush();

            return $this->redirectToRoute('app_taxi_payment', ['id' => $dossier->getId()]);
        }

        return $this->render('taxi/index.html.twig');
    }

    // ── Step 2: Payment page ──
    #[Route('/taxi/payment/{id}', name: 'app_taxi_payment')]
    public function payment(int $id, EntityManagerInterface $em): Response
    {
        $dossier = $em->getRepository(Dossier::class)->find($id);
        if (!$dossier) throw $this->createNotFoundException('Dossier not found');

        return $this->render('taxi/payment.html.twig', $this->buildPaymentVars($dossier));
    }

    // ── Step 3: Confirm payment POST → redirect to success ──
    #[Route('/taxi/payment/{id}/confirm', name: 'app_taxi_confirm_payment', methods: ['POST'])]
    public function confirmPayment(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $dossier = $em->getRepository(Dossier::class)->find($id);
        if (!$dossier) throw $this->createNotFoundException('Dossier not found');

        if (!$this->isCsrfTokenValid('payment_confirm', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $method = $request->request->get('payment_method', $dossier->getPaymentMethod() ?? 'card');
        $dossier->setPaymentMethod($method);
        $em->flush();

        // Redirect to success page (shows download button)
        return $this->redirectToRoute('app_taxi_payment_success', ['id' => $dossier->getId()]);
    }

    // ── Step 4: Success page with download button ──
    #[Route('/taxi/payment/{id}/success', name: 'app_taxi_payment_success')]
    public function paymentSuccess(int $id, EntityManagerInterface $em): Response
    {
        $dossier = $em->getRepository(Dossier::class)->find($id);
        if (!$dossier) throw $this->createNotFoundException('Dossier not found');

        $vars = $this->buildPaymentVars($dossier);
        $vars['paid'] = true;

        return $this->render('taxi/payment.html.twig', $vars);
    }

    // ── Step 5: Generate & download PDF ──
    #[Route('/taxi/contract/{id}', name: 'app_taxi_contract')]
    public function contract(int $id, EntityManagerInterface $em): Response
    {
        $dossier = $em->getRepository(Dossier::class)->find($id);
        if (!$dossier) throw $this->createNotFoundException('Dossier not found');

        $vars = $this->buildPaymentVars($dossier);

        $html = $this->renderView('taxi/contract.html.twig', array_merge($vars, [
            'issuedAt' => (new \DateTime())->format('d/m/Y H:i'),
        ]));

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = sprintf('contract_taxi_%06d_%s.pdf', $dossier->getId(), strtolower($dossier->getLastName()));

        return new Response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // ── Shared helper: build template vars ──
    private function buildPaymentVars(Dossier $dossier): array
    {
        $months      = $dossier->getDurationMonths() ?? 12;
        $totalAmount = number_format(($dossier->getPremium() ?? 0) * $months, 2);
        $endDate     = $dossier->getEndDate()
            ? $dossier->getEndDate()->format('d/m/Y')
            : ($dossier->getStartDate()
                ? (clone $dossier->getStartDate())->modify('+' . $months . ' months -1 day')->format('d/m/Y')
                : '—');

        return [
            'dossier'     => $dossier,
            'totalAmount' => $totalAmount,
            'endDate'     => $endDate,
        ];
    }
}
