<?php

namespace App\Controller\Back;

use App\Entity\Report;
use App\Form\Back\ReportType;
use App\Repository\ReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/report', name: 'report_')]
class ReportController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ReportRepository $reportRepository): Response
    {
        return $this->render('back/report/index.html.twig', [
            'reports' => $reportRepository->findBy([], ['id' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReportRepository $reportRepository): Response
    {
        $report = new Report();
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reportRepository->save($report, true);

            return $this->redirectToRoute('back_report_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/report/new.html.twig', [
            'report' => $report,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Report $report): Response
    {
        return $this->render('back/report/show.html.twig', [
            'report' => $report,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Report $report, ReportRepository $reportRepository): Response
    {
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reportRepository->save($report, true);

            return $this->redirectToRoute('back_report_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/report/edit.html.twig', [
            'report' => $report,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Report $report, ReportRepository $reportRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$report->getId(), $request->request->get('_token'))) {
            $reportRepository->remove($report, true);
        }

        return $this->redirectToRoute('back_report_index', [], Response::HTTP_SEE_OTHER);
    }
}
