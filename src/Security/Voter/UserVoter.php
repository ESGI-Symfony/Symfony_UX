<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    public const BECOME_LESSOR = 'BECOME_LESSOR';
    public const RENTALS = 'RENTALS';
    public const DELETE_ACCOUNTS = 'DELETE_ACCOUNTS';
    public const CREATE_RENTALS = 'CREATE_RENTALS';

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::BECOME_LESSOR, self::RENTALS, self::CREATE_RENTALS, self::DELETE_ACCOUNTS])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var \App\Entity\User $user */
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        $isAdmin = in_array('ROLE_ADMIN', $user->getRoles());

        if (!$user->isVerified()) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            self::BECOME_LESSOR => $isAdmin || !$user->isLessor(),
            self::RENTALS, self::CREATE_RENTALS => $isAdmin || $user->isLessor(),
            self::DELETE_ACCOUNTS => $user !== $subject && $isAdmin,
            default => false,
        };
    }
}
