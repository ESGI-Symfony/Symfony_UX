<?php

namespace App\Controller\Front;

use App\Entity\Rental;
use App\Entity\User;
use App\Entity\UserLessorRequest;
use App\Enums\UserLessorRequestStatus;
use App\Form\Front\LessorRequestFormType;
use App\Form\Front\UserProfileFormType;
use App\Form\RentalFormType;
use App\Repository\UserLessorRequestRepository;
use App\Security\Voter\UserVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

// this route is protected by the firewall
#[Route(path: '/profile', name: 'app_profile_')]
class UserController extends AbstractController
{

    #[Route(path: '/account', name: 'account')]
    public function account(): Response
    {
        $user = $this->getUser();

        return $this->render('front/profile/account.html.twig',  [
            'user' => $user,
        ]);
    }

    #[Route(path: '/edit', name: 'edit')]
    public function edit(Request $request, UserRepository $userRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UserProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_profile_account', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/profile/edit_profile.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);

    }

    #[Route(path: '/bookings', name: 'bookings')]
    public function bookings(): Response
    {
        return $this->render('front/profile/tenant/bookings.html.twig');
    }

}
