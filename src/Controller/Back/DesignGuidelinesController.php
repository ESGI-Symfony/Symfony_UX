<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DesignGuidelinesController extends AbstractController
{
    #[Route(path: '/design-guidelines', name: 'app_back_designguidelines_home')]
    public function home(): Response
    {
        return $this->render('back/design-guidelines/index.html.twig');
    }
}
