<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface; // Needed for deleting
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UsersController extends AbstractController
{
    #[Route('/users', name: 'app_users')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('users/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/users/{id}', name: 'app_users_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        // NOTE: You must create templates/users/show.html.twig
        return $this->render('users/show.html.twig', ['user' => $user]);
    }

    #[Route('/users/{id}/edit', name: 'app_users_edit', methods: ['GET', 'POST'])]
    public function edit(User $user): Response
    {
        // NOTE: You must create templates/users/edit.html.twig
        return $this->render('users/edit.html.twig', ['user' => $user]);
    }

    #[Route('/users/{id}/delete', name: 'app_users_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // This checks the CSRF token we set in your Twig form earlier
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'User deleted successfully.');
        }

        return $this->redirectToRoute('app_users');
    }
}