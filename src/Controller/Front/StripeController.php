<?php

namespace App\Controller\Front;

use App\Entity\Reservation;
use App\Security\Voter\StripeVoter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Charge;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/stripe', name: 'app_stripe_')]
class StripeController extends AbstractController
{
    #[Route('/{id}', name: 'index')]
    public function index(Reservation $reservation): Response
    {
        if ($this->redirectIfAlreadyPaid($reservation)) {
            return $this->render('front/stripe/success.html.twig');
        }
        $this->denyAccessUnlessGranted(StripeVoter::PROCESS_TO_PAYMENT, $reservation);

        return $this->render('front/stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
            'reservation' => $reservation,
        ]);
    }


    #[Route('/{id}/create-charge', name: 'charge', methods: ['POST'])]
    public function createCharge(
        Reservation $reservation,
        Request $request,
        EntityManagerInterface $entityManager
    ): RedirectResponse|Response {
        if ($this->redirectIfAlreadyPaid($reservation)) {
            return $this->render('front/stripe/success.html.twig');
        }
        $this->denyAccessUnlessGranted(StripeVoter::PROCESS_TO_PAYMENT, $reservation);

        try {
            Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
            $token = Charge::create([
                "amount" => $reservation->getTotalPrice() * 100,
                "currency" => "eur",
                "source" => $request->request->get('stripeToken'),
                "description" => "SpaceBnB Reservation number {$reservation->getId()}"
            ]);

            $reservation->setPaymentToken($token->id);
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->render('front/stripe/success.html.twig');
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                'Payment Failed!'
            );
            return $this->redirectToRoute(
                'front_app_stripe_index',
                ['id' => $reservation->getId()],
                Response::HTTP_SEE_OTHER
            );
        }
    }

    private function redirectIfAlreadyPaid(Reservation $reservation): bool
    {
        return $reservation->getPaymentToken() && $this->getUser() === $reservation->getBuyer();
    }
}
