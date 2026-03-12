<?php
namespace App\Controller;
use App\Entity\Dossier;
use App\Repository\DossierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
final class ProsController extends AbstractController
{
    #[Route('/pros', name: 'app_pros')]
    public function index(DossierRepository $dossierRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $dossiers = $dossierRepository->findAll();
        } else {
            $dossiers = $dossierRepository->findBy(['assignedTo' => $this->getUser()]);
        }
        return $this->render('pros/index.html.twig', [
            'dossiers' => $dossiers,
        ]);
    }
    #[Route('/pros/{id}', name: 'app_pros_show')]
    public function show(Dossier $dossier): Response
    {
        return $this->render('pros/show.html.twig', [
            'dossier' => $dossier,
        ]);
    }
    #[Route('/pros/{id}/claim', name: 'app_pros_claim', methods: ['POST'])]
    public function claim(Dossier $dossier, EntityManagerInterface $em): Response
    {
        if ($dossier->getStatus() === 'pending') {
            $dossier->setStatus('claimed');
            $dossier->setClaimedBy($this->getUser());
            $em->flush();
            $this->addFlash('success', 'You are now handling this dossier.');
        }
        return $this->redirectToRoute('app_pros');
    }
    #[Route('/pros/{id}/review', name: 'app_pros_submit_review', methods: ['POST'])]
    public function submitReview(Dossier $dossier, EntityManagerInterface $em): Response
    {
        if ($dossier->getStatus() === 'claimed') {
            $dossier->setStatus('review');
            $em->flush();
            $this->addFlash('success', 'Dossier sent to admin for review.');
        }
        return $this->redirectToRoute('app_pros');
    }
    #[Route('/pros/{id}/approve', name: 'app_pros_approve', methods: ['POST'])]
    public function approve(Dossier $dossier, EntityManagerInterface $em): Response
    {
        $dossier->setStatus('approved');
        $em->flush();
        $this->addFlash('success', 'Dossier approved successfully.');
        return $this->redirectToRoute('app_pros');
    }
    #[Route('/pros/{id}/reject', name: 'app_pros_reject', methods: ['POST'])]
    public function reject(Dossier $dossier, Request $request, EntityManagerInterface $em): Response
    {
        $note = $request->request->get('rejection_note', '');
        $dossier->setStatus('rejected');
        $dossier->setRejectionNote($note);
        $em->flush();
        $this->addFlash('error', 'Dossier rejected with feedback sent to commercial.');
        return $this->redirectToRoute('app_pros');
    }

    // ── DELETE — admin only ──
    #[Route('/pros/{id}/delete', name: 'app_pros_delete', methods: ['POST'])]
    public function delete(Dossier $dossier, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if (!$this->isCsrfTokenValid('delete_' . $dossier->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $name = $dossier->getFirstName() . ' ' . $dossier->getLastName();
        $em->remove($dossier);
        $em->flush();

        $this->addFlash('success', 'Dossier for ' . $name . ' has been deleted.');
        return $this->redirectToRoute('app_pros');
    }

    #[Route('/pros/{id}/upload', name: 'app_pros_upload', methods: ['POST'])]
    public function upload(Dossier $dossier, Request $request, EntityManagerInterface $em): Response
    {
        $files = $request->files->get('files', []);
        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/dossiers/';
        $uploaded = $dossier->getUploadedFiles() ?? [];
        foreach ($files as $file) {
            if ($file->isValid()) {
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $file->move($uploadDir, $filename);
                $uploaded[] = $filename;
            }
        }
        $dossier->setUploadedFiles($uploaded);
        $em->flush();
        $this->addFlash('success', count($files) . ' file(s) uploaded successfully.');
        return $this->redirectToRoute('app_pros_show', ['id' => $dossier->getId()]);
    }
}