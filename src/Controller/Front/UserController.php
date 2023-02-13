<?php

namespace App\Controller\Front;

use App\Entity\Rental;
use App\Entity\User;
use App\Entity\UserLessorRequest;
use App\Enums\UserLessorRequestStatus;
use App\Form\LessorRequestFormType;
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


}
