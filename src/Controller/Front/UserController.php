<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Entity\UserLessorRequest;
use App\Enums\UserLessorRequestStatus;
use App\Form\LessorRequestFormType;
use App\Repository\UserLessorRequestRepository;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use Couchbase\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

// this route is protected by the firewall
#[Route(path: '/profile', name: 'app_profile_')]
class UserController extends AbstractController
{

    #[Route(path: '/rentals', name: 'rentals')]
    public function rentals(): Response
    {
        if(!$this->isGranted(UserVoter::RENTALS, $this->getUser())) {
            if ($this->isGranted(UserVoter::BECOME_LESSOR, $this->getUser())) {
                return $this->redirectToRoute('app_profile_become_lessor');
            }
            return $this->redirectToRoute('app_home');
        }
        return $this->render('front/profile/lessor/rentals.html.twig');
    }

    #[Route(path: '/become-lessor', name: 'become_lessor')]
    public function becomeLessor(Request $request, EntityManagerInterface $entityManager, UserLessorRequestRepository $userLessorRequestRepository): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::BECOME_LESSOR, $this->getUser());

        /** @var User $user */
        $user = $this->getUser();

        $hasPendingRequest = !!$userLessorRequestRepository->findOneBy(['lessor' => $user, 'status' => UserLessorRequestStatus::Pending]);
        if($hasPendingRequest) {
            return $this->render('front/profile/lessor/become_lessor_success.html.twig');
        }

        $lessorRequest = new UserLessorRequest;
        $form = $this->createForm(LessorRequestFormType::class, $lessorRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setFirstname($form->get('firstname')->getData());
            $user->setLastname($form->get('lastname')->getData());
            $user->setPhone($form->get('phone')->getData());
            $user->setLessorNumber($form->get('lessor_number')->getData());
            $entityManager->persist($user);

            $lessorRequest->setLessor($user)
                ->setStatus(UserLessorRequestStatus::Pending);
            $entityManager->persist($lessorRequest);

            $entityManager->flush();

            return $this->render('front/profile/lessor/become_lessor_success.html.twig');
        }

        return $this->render('front/profile/lessor/become_lessor.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/bookings', name: 'bookings')]
    public function bookings(): Response
    {
        return $this->render('front/profile/tenant/bookings.html.twig');
    }

}
