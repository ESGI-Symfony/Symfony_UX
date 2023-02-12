<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Exception\EmailNotVerifiedException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

class CheckVerifiedUserSubscriber implements EventSubscriberInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // -10 to prevent exposing that user is verified before checking password
            CheckPassportEvent::class => ['onCheckPassport', -10],
            LoginFailureEvent::class => 'onLoginFailure',
        ];
    }

    public function onCheckPassport(CheckPassportEvent $event)
    {
        $passport = $event->getPassport();

        $user = $passport->getUser();
        if (!$user instanceof User) {
            throw new \Exception('Unexpected user type');
        }

        if (!$user->isVerified()) {
            throw new EmailNotVerifiedException($user);
        }
    }

    public function onLoginFailure(LoginFailureEvent $event)
    {
        if (!$event->getException() instanceof EmailNotVerifiedException) {
            return;
        }

        // catch when a user tries to log with an unverified email, to resend a verification email
        $response = new RedirectResponse(
            $this->router->generate('app_verify_email_sent', [
                    'uuid' => $event->getException()->getUser()->getUuid(),
                ]
            )
        );
        $event->setResponse($response);
    }
}