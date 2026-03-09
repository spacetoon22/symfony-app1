<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class EditController extends AbstractController
{
    #[Route('/edit/{id}', name: 'app_edit', methods: ['GET', 'POST'])]
    public function edit(
        int $id,
        Request $request,
        UserRepository $repo,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em
    ): Response {

        $user = $repo->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        if ($request->isMethod('POST')) {
            $new     = $request->request->get('new_password');
            $confirm = $request->request->get('confirm_password');

            if (empty($new)) {
                $this->addFlash('error', 'New password cannot be empty.');
            } elseif (strlen($new) < 8) {
                $this->addFlash('error', 'Password must be at least 8 characters.');
            } elseif ($new !== $confirm) {
                $this->addFlash('error', 'Passwords do not match.');
            } else {
                $user->setPassword($hasher->hashPassword($user, $new));
                $em->flush();
                $this->addFlash('success', 'Password updated successfully.');
                return $this->redirectToRoute('app_users');
            }
        }

        return $this->render('edit/index.html.twig', ['user' => $user]);
    }
}