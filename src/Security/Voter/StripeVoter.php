<?php

namespace App\Security\Voter;

use App\Entity\Reservation;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class StripeVoter extends Voter
{
    public const PROCESS_TO_PAYMENT = 'PROCESS_TO_PAYMENT';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::PROCESS_TO_PAYMENT])
            && $subject instanceof Reservation;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var Reservation $reservation */
        $reservation = $subject;
        /** @var User $user */
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface || !$reservation instanceof Reservation) {
            return false;
        }

        if (!$user->isVerified()) {
            return false;
        }

        if($reservation->getBuyer() !== $user) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            self::PROCESS_TO_PAYMENT => $reservation->getPaymentToken() === null,
            default => false,
        };
    }
}
