<?php

namespace App\Controller\Back;

use App\Entity\Rental;
use App\Form\Back\RentalType;
use App\Repository\RentalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/rental', name: 'rental_')]
class RentalController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(RentalRepository $rentalRepository): Response
    {
        return $this->render('back/rental/index.html.twig', [
            'rentals' => $rentalRepository->findBy([], ['id' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, RentalRepository $rentalRepository): Response
    {
        $rental = new Rental();
        $rental->setUuid(Uuid::v6());
        $form = $this->createForm(RentalType::class, $rental);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rentalRepository->save($rental, true);

            return $this->redirectToRoute('back_rental_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/rental/new.html.twig', [
            'rental' => $rental,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Rental $rental): Response
    {
        return $this->render('back/rental/show.html.twig', [
            'rental' => $rental,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rental $rental, RentalRepository $rentalRepository): Response
    {
        $form = $this->createForm(RentalType::class, $rental);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rentalRepository->save($rental, true);

            return $this->redirectToRoute('back_rental_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/rental/edit.html.twig', [
            'rental' => $rental,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Rental $rental, RentalRepository $rentalRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rental->getId(), $request->request->get('_token'))) {
            $rentalRepository->remove($rental, true);
        }

        return $this->redirectToRoute('back_rental_index', [], Response::HTTP_SEE_OTHER);
    }
}
