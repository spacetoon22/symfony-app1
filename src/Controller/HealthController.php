<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Dossier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HealthController extends AbstractController
{
    #[Route('/health', name: 'app_health')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $submitted = false;

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
            $dossier->setType('health');
            // $dossier->setStatus('pending');



            // ADD THIS ↓
            $commercialEmail = $request->request->get('commercial_email', '');
            $commercial = $em->getRepository(User::class)->findOneBy(['email' => $commercialEmail]);

            if (!$commercial) {
                $this->addFlash('error', 'No commercial found with email: ' . $commercialEmail);
                return $this->render('health/index.html.twig');
            }

            
            $dossier->setAssignedTo($commercial);
            $dossier->setClaimedBy($commercial);  // auto-claim at submission
            $dossier->setStatus('claimed');        // skip "pending", go straight to claimed


            $ben = $request->request->get('beneficiaries');
            if ($ben !== null && $ben !== '') {
                $dossier->setBeneficiaries((int) $ben);
            }

            $premium = $request->request->get('premium');
            if ($premium !== null && $premium !== '') {
                $dossier->setPremium((float) $premium);
            }

            $dob = $request->request->get('dob');
            if ($dob) {
                $dossier->setDob(new \DateTime($dob));
            }

            $startDate = $request->request->get('start_date');
            if ($startDate) {
                $dossier->setStartDate(new \DateTime($startDate));
            }

            // ── Handle file uploads ──
            $uploadedFiles = $request->files->get('files');
            $savedFilenames = [];

            if ($uploadedFiles) {
                // uploads go to: public/uploads/dossiers/
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/dossiers';

                // Create folder if it doesn't exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0775, true);
                }

                foreach ($uploadedFiles as $file) {
                    if ($file && $file->isValid()) {
                        // Safe unique filename: timestamp_randomhex_originalname
                        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $extension    = $file->guessExtension() ?? $file->getClientOriginalExtension();
                        $safeFilename = time() . '_' . bin2hex(random_bytes(4)) . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName) . '.' . $extension;

                        $file->move($uploadDir, $safeFilename);
                        $savedFilenames[] = $safeFilename;
                    }
                }
            }

            // Store filenames as JSON in the uploadedFiles column
            $dossier->setUploadedFiles($savedFilenames);

            $em->persist($dossier);
            $em->flush();

            $submitted = true;
        }

        return $this->render('health/index.html.twig', [
            'submitted' => $submitted,
        ]);
    }
}