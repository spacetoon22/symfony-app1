<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function __construct(private UserRepository $userRepository) {}

    /**
     * Symfony calls this with whatever was typed in the login field.
     * We try email first, then username.
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        // Try by email first
        $user = $this->userRepository->findOneBy(['email' => $identifier]);

        // If not found, try by username
        if (!$user) {
            $user = $this->userRepository->findOneBy(['username' => $identifier]);
        }

        if (!$user) {
            throw new UserNotFoundException(sprintf('User "%s" not found.', $identifier));
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return $class === \App\Entity\User::class || is_subclass_of($class, \App\Entity\User::class);
    }
}