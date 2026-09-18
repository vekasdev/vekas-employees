<?php

namespace App\Controller;

use App\Entity\Attendence;
use App\Entity\Employee;
use App\Form\AttendenceType;
use App\Repository\AttendenceRepository;
use App\Service\AttendenceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/attendence')]
final class AttendenceController extends AbstractController
{

    function __construct(
        private AttendenceService $attendenceService
    )
    {}
    
    #[Route(name: 'app_attendence_index', methods: ['GET'])]
    public function index(AttendenceRepository $attendenceRepository): Response
    {
        return $this->render('attendence/index.html.twig', [
            'attendences' => $attendenceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_attendence_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $attendence = new Attendence();
        $form = $this->createForm(AttendenceType::class, $attendence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($attendence);
            $entityManager->flush();

            return $this->redirectToRoute('app_attendence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('attendence/new.html.twig', [
            'attendence' => $attendence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_attendence_show', methods: ['GET'])]
    public function show(Attendence $attendence): Response
    {
        return $this->render('attendence/show.html.twig', [
            'attendence' => $attendence,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_attendence_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Attendence $attendence, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AttendenceType::class, $attendence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $session = $request->getSession();
            $session->clear();

            return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('attendence/edit.html.twig', [
            'attendence' => $attendence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_attendence_delete', methods: ['POST'])]
    public function delete(Request $request, Attendence $attendence, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$attendence->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($attendence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_attendence_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route("/check-in/{employee}","check-in")]
    function checkIn(Employee $employee) {
        $this->attendenceService->checkIn($employee);
        return $this->redirectToRoute("app_employee_index");
    }

    #[Route("/check-out/{employee}","check-out")]
    function checkOut(Employee $employee) {
        $this->attendenceService->checkOut($employee);
        return $this->redirectToRoute("app_employee_index");
    }
}
